<?php
require_once('/Users/stanoje/Local Sites/ac-taverne/app/public/wp-load.php');

$page = get_page_by_path('stagione');
if (!$page) {
    // try by title
    $page = get_page_by_title('Stagione');
}

if ($page) {
    $table_content = '
<!-- wp:table {"className":"is-style-regular"} -->
<figure class="wp-block-table is-style-regular"><table>
<thead><tr><th>POS</th><th>SQUADRA</th><th>G</th><th>V</th><th>N</th><th>P</th><th>Pti</th></tr></thead>
<tbody>
<tr><td>1</td><td>SC YF Juventus</td><td>28</td><td>22</td><td>3</td><td>3</td><td>69</td></tr>
<tr><td>2</td><td>AC Taverne</td><td>28</td><td>18</td><td>4</td><td>6</td><td>58</td></tr>
<tr><td>3</td><td>FC Tuggen</td><td>28</td><td>18</td><td>4</td><td>6</td><td>58</td></tr>
<tr><td>4</td><td>FC Wettswil-Bonstetten</td><td>28</td><td>17</td><td>5</td><td>6</td><td>56</td></tr>
<tr><td>5</td><td>FC Dietikon</td><td>28</td><td>11</td><td>8</td><td>9</td><td>41</td></tr>
<tr><td>6</td><td>FC Baden 1897</td><td>28</td><td>11</td><td>7</td><td>10</td><td>40</td></tr>
<tr><td>7</td><td>FC Winterthur U-21</td><td>28</td><td>10</td><td>9</td><td>9</td><td>39</td></tr>
<tr><td>8</td><td>FC Freienbach</td><td>28</td><td>10</td><td>7</td><td>11</td><td>37</td></tr>
<tr><td>9</td><td>FC Kosova</td><td>28</td><td>9</td><td>9</td><td>10</td><td>36</td></tr>
<tr><td>10</td><td>FC Mendrisio</td><td>28</td><td>10</td><td>6</td><td>12</td><td>36</td></tr>
<tr><td>11</td><td>FC Collina d\'Oro</td><td>28</td><td>9</td><td>8</td><td>11</td><td>35</td></tr>
<tr><td>12</td><td>FC St. Gallen 1879 U-21</td><td>28</td><td>9</td><td>4</td><td>15</td><td>31</td></tr>
<tr><td>13</td><td>USV Eschen/Mauren</td><td>28</td><td>6</td><td>11</td><td>11</td><td>29</td></tr>
<tr><td>14</td><td>SV Höngg</td><td>28</td><td>5</td><td>6</td><td>17</td><td>21</td></tr>
<tr><td>15</td><td>FC Widnau</td><td>28</td><td>5</td><td>6</td><td>17</td><td>21</td></tr>
<tr><td>16</td><td>SV Schaffhausen</td><td>28</td><td>3</td><td>5</td><td>20</td><td>14</td></tr>
</tbody>
</table></figure>
<!-- /wp:table -->';

    wp_update_post([
        'ID' => $page->ID,
        'post_content' => $table_content
    ]);
    
    echo "Classifica aggiornata sulla pagina: " . $page->post_title;
} else {
    echo "Pagina 'Stagione' non trovata.";
}
