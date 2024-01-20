<tr>

  <th class="text-center info">

    <span class="text-primary">

      <p>閲覧のみメンバー名リスト</p>
      <p>（リストはカンマ区切りで複数の値を指定してください）</p>

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <textarea name="chatwork_members_readonly_names"
              cols="72"
              rows="5"><?php
                         print($account['chatwork_members_readonly_names'])
                       ?></textarea>

  </td>

</tr>
