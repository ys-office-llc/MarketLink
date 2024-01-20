<tr>
  <th class="text-center info">
    <span class="text-primary">ハードオフ</span>
  </th>

  <td class="active" style="width: 600px">

    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="rank_hardoff[]"
               value="N"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'N',
                $research_new_arrival['rank_hardoff'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        N /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_hardoff[]"
               value="S"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'S',
                $research_new_arrival['rank_hardoff'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        S /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_hardoff[]"
               value="A"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'A',
                $research_new_arrival['rank_hardoff'],
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
               name="rank_hardoff[]"
               value="B"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'B',
                $research_new_arrival['rank_hardoff'],
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
               name="rank_hardoff[]"
               value="C"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'C',
                $research_new_arrival['rank_hardoff'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        C /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_hardoff[]"
               value="D"
    <?php if (!is_null($research_new_arrival['rank_hardoff']) and
              in_array(
                'D',
                $research_new_arrival['rank_hardoff'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        D
      </label>
    
    </div>

  </td>
</tr>
