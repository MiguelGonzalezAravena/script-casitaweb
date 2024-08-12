<?php
function template_main() {
  global $context, $settings, $options, $txt, $db_prefix, $scripturl, $modSettings, $tranfer1, $boardurl;

  $count = 1;
  $contar = 1;
  $saksdmpas2 = 1;
  $contar2 = 1;
  $contar3 = 1;
  $contar4 = 1;
  $contar5 = 1;
  $contar22 = 1;
  $contar6 = 1;
  $contar7 = 1;
  $contar25 = 1;
  $contar9 = 1;
  $contar8 = 1;
  // TO-DO: Colocar HTML dentro de echo

  echo '
    <div>
      <div class="box_300" align="left" style="float: left; margin-right: 8px;">
        <div class="box_title" style="width: 300px;">
          <div class="box_txt box_300-34">10 Posts m&aacute;s comentados</div>
          <div class="box_rss">
            <div class="icon_img">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['tcomentados'] as $total) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . achicar($total['subject']) . '" href="' . $boardurl . '/post/' . $total['id'] . '/' . urls($total['description']) . '/' . urls($total['subject']) . '.html">' . achicar($total['subject']) . '</a>
        (' . $total['cuenta'] . ' com)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left; margin-right: 8px;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Posts m&aacute;s vistos</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['top_topics_views'] as $topic) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $topic['subject'] . '" href="' . $topic['href'] . '">' . achicar($topic['subject']) . '</a>
        (' . $topic['num_views'] . ' vis)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Post con m&aacute;s puntos</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['postporpuntos'] as $ppp) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $ppp['titulo'] . '" href="' . $boardurl . '/post/' . $ppp['id'] . '/' . urls($ppp['description']) . '/' . urls($ppp['titulo']) . '.html">' . achicar($ppp['titulo']) . '</a>
        (' . $ppp['puntos'] . ' pts)
      </span>
      <br />';
  }

  $count = 1;
  
  echo '
        </div>
      </div>
  </div>
  <div style="clear: left;"></div>
  <div style="margin-top: 8px;">
    <div class="box_300" align="left" style="float: left; margin-right: 8px;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Principales posteadores</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['tuser'] as $poster) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . censorText($poster['realName']) . '" href="' . $boardurl . '/perfil/' . $poster['realName'] . '">' . censorText($poster['realName']) . '</a>
        (' . $poster['cuenta'] . ' post)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left; margin-right: 8px;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Usuarios con m&aacute;s puntos</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['shop_richest'] as $row) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $row['realName'] . '" href="' . $boardurl . '/perfil/' . $row['realName'] . '">' . $row['realName'] . '</a>
        (' . $row['money'] . ' pts)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left;">
      <div class="box_title" style="width: 300px;"><div class="box_txt box_300-34">10 Usuarios que m&aacute;s comentan</div>
      <div class="box_rss">
        <div class="icon_img">
          <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
        </div>
      </div>
    </div>
    <div class="windowbg" style="width: 292px; padding: 4px;">';

  $order = array();
  $r = db_query("
    SELECT id_user, COUNT(id_user) AS \"Rows\"
    FROM {$db_prefix}comentarios
    GROUP BY id_user
    ORDER BY COUNT(id_user) DESC
    LIMIT 30", __FILE__, __LINE__);

  while ($row = mysqli_fetch_assoc($r)) {
    $r2 = db_query("
      SELECT ID_MEMBER, COUNT(ID_MEMBER) AS \"Rowsd\"
      FROM {$db_prefix}gallery_comment
      WHERE ID_MEMBER = {$row['id_user']}
      GROUP BY ID_MEMBER
      ORDER BY Rowsd DESC
      LIMIT 10", __FILE__, __LINE__);

    while ($row2 = mysqli_fetch_assoc($r2)) {
      $sers = db_query("
        SELECT ID_MEMBER, realName
        FROM {$db_prefix}members
        WHERE ID_MEMBER = {$row2['ID_MEMBER']}
        LIMIT 10", __FILE__, __LINE__);

      while ($grup = mysqli_fetch_assoc($sers)) {
        $order[$grup['realName']] = ($row2['Rowsd'] + $row['Rows']);
      }
    }
  }

  arsort($order);

  $order = array_slice($order, 0, 10);

  foreach ($order as $username => $comments) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a href="' . $boardurl . '/perfil/' . $username . '" title="' . $username . '">' . $username . '</a>
        (' . $comments . ' com)
      </span>
      <br />';
  }

  $count = 1;

  echo '
        </div>
      </div>
    </div>
    <div style="clear: left;"></div>
    <div style="margin-top: 8px;">
      <div class="box_300" align="left" style="float: left; margin-right: 8px;">
        <div class="box_title" style="width: 300px;">
          <div class="box_txt box_300-34">10 Im&aacute;genes m&aacute;s comentadas</div>
          <div class="box_rss">
            <div class="icon_img">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['comment-img2'] as $poster) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $poster['title'] . '" href="' . $boardurl . '/imagenes/ver/' . $poster['id'] . '">' . achicar($poster['title']) . '</a>
        (' . $poster['commenttotal'] . ' com)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left; margin-right: 8px;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Im&aacute;genes m&aacute;s vistas</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['imgv'] as $imgv) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . censorText($imgv['titulo']) . '" href="' . $boardurl . '/imagenes/ver/' . $imgv['id'] . '">' . achicar($imgv['titulo']) . '</a>
        (' . $imgv['v'] . ' vis)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Im&aacute;genes con m&aacute;s puntos</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['comment-img3'] as $topic) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . censorText($topic['title']) . '" href="' . $boardurl . '/imagenes/ver/' . $topic['id'] . '">' . achicar($topic['title']) . '</a>
        (' . $topic['puntos'] . ' pts)
      </span>
      <br />';
  }

  $count = 1;

  echo '
        </div>
      </div>
    </div>
    <div style="clear: left;"></div>
    <div style="margin-top: 8px;">
      <div class="box_300" align="left" style="float: left; margin-right: 8px;">
        <div class="box_title" style="width: 300px;">
          <div class="box_txt box_300-34">10 Muros m&aacute;s comentados</div>
          <div class="box_rss">
            <div class="icon_img">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
            </div>
          </div>
        </div>
        <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['muroc'] as $cmuro) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $cmuro['realName'] . '" href="' . $boardurl . '/perfil/' . $cmuro['realName'] . '">' . $cmuro['realName'] . '</a>
        (' . $cmuro['cuenta'] . ' mjs)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left; margin-right: 8px;">
      <div class="box_title" style="width: 300px;">
        <div class="box_txt box_300-34">10 Usuarios con m&aacute;s im&aacute;genes</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">';

  foreach ($context['masi'] as $imas) {
    echo '
      <span class="size11">
        <b>' . $count++ . '.</b>
        <a title="' . $imas['realName'] . '" href="' . $boardurl . '/perfil/' . $imas['realName'] . '">' . $imas['realName'] . '</a>
        (' . $imas['cuenta'] . ' img)
      </span>
      <br />';
  }

  $count = 1;

  echo '
      </div>
    </div>
    <div class="box_300" align="left" style="float: left;">
      <div class="box_title" style="x">
        <div class="box_txt box_300-34">Publicidad</div>
        <div class="box_rss">
          <div class="icon_img">
            <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 16px; height: 16px;" border="0" />
          </div>
        </div>
      </div>
      <div class="windowbg" style="width: 292px; padding: 4px;">
        <script type="text/javascript">
          <!--
          google_ad_client = "pub-5583945616614902";
          /* 300x250, creado 19/07/09 */
          google_ad_slot = "3426331033";
          google_ad_width = 285;
          google_ad_height = 135;
          //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      </div>
    </div>
  </div>
  <div style="clear: left;"></div>';

  if (!empty($context['monthly']) & ($context['user']['is_admin'])) {
    echo '
      <div style="margin-top:8px;">
        <div class="box_buscador">
          <div class="box_title" style="width: 920px;">
            <div class="box_txt box_buscadort">
              <center>Historia del foro (usando diferencia horaria del foro)</center>
            </div>
            <div class="box_rss">
              <img alt="" src="' . $tranfer1 . '/blank.gif" style="width: 14px; height: 12px;" border="0" />
            </div>
          </div>
          <div style="width: 920px;" class="windowbg">
            <table border="0" width="100%" cellspacing="1" cellpadding="4" style="margin-bottom: 1ex;" id="stats">
              <tr class="titlebg" valign="middle">
                <td width="25%">Mes</td>
                <td width="15%">Posts nuevos</td>
                <td width="15%">Usuarios nuevos</td>';

    if (!empty($modSettings['hitStats'])) {
      echo '<td>P&aacute;gina vistas</td>';
    }

    echo '</tr>';

  foreach ($context['monthly'] as $month) {
    echo '
      <tr class="windowbg2" valign="middle" id="tr_' . $month['id'] . '">
        <th align="left" width="25%">
          <a name="' . $month['id'] . '" id="link_' . $month['id'] . '" href="' . $month['href'] . '" onclick="return doingExpandCollapse || expand_collapse(\'' . $month['id'] . '\', ' . $month['num_days'] . ');">
            ' . $month['month'] . ' ' . $month['year'] . '
          </a>
        </th>
        <th width="15%">' . $month['new_topics'] . '</th>
        <th width="15%">' . $month['new_members'] . '</th>';

    if (!empty($modSettings['hitStats'])) {
      echo '<th>' . $month['hits'] . '</th>';
    }

    echo '</tr>';

    if ($month['expanded']) {
      foreach ($month['days'] as $day) {
        echo '
          <tr class="windowbg2" valign="middle" align="left">
            <td align="left" style="padding-left: 3ex;">' . $day['year'] . '-' . $day['month'] . '-' . $day['day'] . '</td>
            <td>' . $day['new_topics'] . '</td>
            <td>' . $day['new_members'] . '</td>';

        if (!empty($modSettings['hitStats'])) {
          echo '<td>' . $day['hits'] . '</td>';
        }

        echo '</tr>';
      }
    }
  }

  echo '
            </table>
          </div>
        </div>
      </div>
      <div style="clear: left;"></div>';
  }
}

?>