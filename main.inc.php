<?php
/*
Plugin Name: Album Navigation
Version: 16.a
Description: Navigation between sibling albums and back to the parent album.
Plugin URI: https://piwigo.org/ext/extension_view.php?eid=1117
Author: Luc Chapon
Author URI: https://github.com/sxilderik
License: GPL-2.0-or-later
Has Settings: false
*/

if (!defined('PHPWG_ROOT_PATH')) {
    die('Hacking attempt!');
}

add_event_handler('loc_end_index', 'album_navigation_add_buttons');

function album_navigation_add_buttons()
{
    global $page, $template, $user, $conf;

    load_language('plugin.lang', __DIR__ . '/');

    // Uniquement dans un album réel.
    if (
        !isset($page['section'])
        || $page['section'] !== 'categories'
        || empty($page['category']['id'])
        || empty($page['category']['id_uppercat'])
    ) {
        return;
    }

    $current_id = (int) $page['category']['id'];
    $parent_id = (int) $page['category']['id_uppercat'];

    // Informations sur le parent.
    $parent = get_cat_info($parent_id);

    if (empty($parent)) {
        return;
    }

    // Même sélection que Piwigo pour les sous-albums affichables :
    // droits de l'utilisateur, visibilité, albums non vides, ordre Piwigo.
    $query = '
SELECT c.*
FROM '.CATEGORIES_TABLE.' c
INNER JOIN '.USER_CACHE_CATEGORIES_TABLE.' ucc
  ON c.id = ucc.cat_id
 AND ucc.user_id = '.$user['id'].'
WHERE c.id_uppercat = '.$parent_id.'
  AND ucc.count_images > 0
'.get_sql_condition_FandF(
        array('visible_categories' => 'c.id'),
        'AND'
    ).'
ORDER BY c.rank
;';

    $result = pwg_query($query);

    $siblings = array();

    while ($row = pwg_db_fetch_assoc($result)) {
        $siblings[] = $row;
    }

    $position = null;

    foreach ($siblings as $i => $sibling) {
        if ((int) $sibling['id'] === $current_id) {
            $position = $i;
            break;
        }
    }

    if ($position === null) {
        return;
    }

    // ← Album précédent.
    if ($position > 0) {
        $url = make_index_url(
            array('category' => $siblings[$position - 1])
        );

        $template->add_index_button(
            '<a href="'.htmlspecialchars($url).'"'
            .' class="pwg-button"'
            .' title="'.l10n('album_navigation_previous').'">'
            .'<span class="pwg-icon pwg-icon-left-open"></span>'
            .'</a>',
            10
        );
    }

    // ↑ Album parent, sur la page contenant l'album courant.
    $per_page = max(1, (int) $conf['nb_categories_page']);
    $startcat = intdiv($position, $per_page) * $per_page;

    $url = make_index_url(
        array('category' => $parent)
    );

    if ($startcat > 0) {
        $url .= '/startcat-'.$startcat;
    }

    $template->add_index_button(
        '<a href="'.htmlspecialchars($url).'"'
        .' class="pwg-button"'
        .' title="'.l10n('album_navigation_parent').'">'
        .'<span class="pwg-icon pwg-icon-up-open"></span>'
        .'</a>',
        11
    );

    // → Album suivant.
    if ($position < count($siblings) - 1) {
        $url = make_index_url(
            array('category' => $siblings[$position + 1])
        );

        $template->add_index_button(
            '<a href="'.htmlspecialchars($url).'"'
            .' class="pwg-button"'
            .' title="'.l10n('album_navigation_next').'">'
            .'<span class="pwg-icon pwg-icon-right-open"></span>'
            .'</a>',
            12
        );
    }
}

