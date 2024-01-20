<tr>

  <th class="text-center info">

    <span class="text-primary">バケーション設定</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <input type="text"
           name="vacation_begin_date"
           id="datepicker_1"
           value="<?php print $account['vacation_begin_date'] ?>"
           size="8" />

    ～

    <input type="text"
           name="vacation_end_date"
           id="datepicker_2"
           value="<?php print $account['vacation_end_date'] ?>"
           size="8" />
  </td>


</tr>

<script>

  $(function(){
    $("#datepicker_1").datepicker({ dateFormat: 'yy-mm-dd' });
  });

  $(function(){
    $("#datepicker_2").datepicker({ dateFormat: 'yy-mm-dd' });
  });

</script>
