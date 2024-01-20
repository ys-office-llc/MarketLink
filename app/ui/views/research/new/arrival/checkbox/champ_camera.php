<tr>
  <th class="text-center info">
    <span class="text-primary">チャンプカメラ</span>
  </th>

  <td class="active" style="width: 600px">

    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="AA"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'AA',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        AA（新品）/
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="A"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'A',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        A（美品）/
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="AB+"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'AB+',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        AB+（程度上）/
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="AB"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'AB',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        AB（良品）/
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="B"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'B',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        B（並品）/
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="C"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'C',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        C（中古）/
      </label>

      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="D"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'D',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        D（難有り）/
      </label>

      <label>
        <input type="checkbox"
               name="rank_champ_camera[]"
               value="E"
    <?php if (!is_null($research_new_arrival['rank_champ_camera']) and
              in_array(
                'E',
                $research_new_arrival['rank_champ_camera'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        E（ジャンク品）
      </label>
    
    </div>

  </td>
</tr>
