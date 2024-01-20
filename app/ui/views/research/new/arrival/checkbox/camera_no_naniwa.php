<tr>
  <th class="text-center info">
    <span class="text-primary">カメラのナニワ</span>
  </th>

  <td class="active" style="width: 600px">

    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="A"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'A',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        A /
      </label>

      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="AB+"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'AB+',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        AB+ /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="AB"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'AB',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        AB /
      </label>

      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="B+"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'B+',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        B+ /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="B"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'B',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        B /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_camera_no_naniwa[]"
               value="C"
    <?php if (!is_null($research_new_arrival['rank_camera_no_naniwa']) and
              in_array(
                'C',
                $research_new_arrival['rank_camera_no_naniwa'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        C
      </label>
    
    </div>

  </td>
</tr>
