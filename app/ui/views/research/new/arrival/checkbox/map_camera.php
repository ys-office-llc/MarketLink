<tr>
  <th class="text-center info">
    <span class="text-primary">マップカメラ</span>
  </th>

  <td class="active" style="width: 600px">

    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="未使用品"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                '未使用品',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        未使用品 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="新同品"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                '新同品',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        新同品 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="美品"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                '美品',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        美品 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="良上品"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                '良上品',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        良上品 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="並品"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                '並品',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        並品 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_map_camera[]"
               value="ジャンク"
    <?php if (!is_null($research_new_arrival['rank_map_camera']) and
              in_array(
                'ジャンク',
                $research_new_arrival['rank_map_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        ジャンク
      </label>
    
    </div>

  </td>
</tr>
