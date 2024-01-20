<tr>

  <th class="text-center info">

    <span class="text-primary">管理ID</span>

  </th>

  <td class="active">

    <?php print $information['id'] ?>

  </td>

</tr>

<tr>

  <th class="text-center info">

    <span class="text-primary">作成日時</span>

  </th>

  <td class="active">

    <?php print $information['created_at'] ?>

  </td>

</tr>

<tr>

  <th class="text-center info">

    <span class="text-primary">変更日時</span>

  </th>

  <td class="active">

    <?php print $information['modified_at'] ?>

  </td>

</tr>

<input type="hidden"
       name="id"
       value="<?php print $information['id'] ?>" />

<input type="hidden"
       name="deleted"
       value="<?php print $information['deleted'] ?>" />

<input type="hidden"
       name="created_at"
       value="<?php print $information['created_at'] ?>" />

<input type="hidden"
       name="modified_at"
       value="<?php print $information['modified_at'] ?>" />

<input type="hidden"
       name="deleted_at"
       value="<?php print $information['deleted_at'] ?>" />
