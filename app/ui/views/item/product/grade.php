<tr>
  <th class="text-center info">
    <span class="text-primary">グレード</span>
  </th>

  <td class="active" style="width: 700px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($item['grade'] === 'chatwork'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="grade"
                     value="chatwork"
                     autocomplete="off"
          <?php if ($item['grade'] === 'chatwork'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              新品
            </label>

          <?php if ($item['grade'] === 'watchlist'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="grade"
                     value="watchlist"
                     autocomplete="off"
          <?php if ($item['grade'] === 'watchlist'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              美品
            </label>


          <?php if ($item['grade'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="grade"
                     value="all"
                     autocomplete="off"
          <?php if ($item['grade'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              良品
            </label>

          <?php if ($item['grade'] === 'do_nothing'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="grade"
                     value="do_nothing"
                     autocomplete="off"
          <?php if ($item['grade'] === 'do_nothing'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              並品
            </label>

          <?php if ($item['grade'] === 'do_nothing'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="grade"
                     value="do_nothing"
                     autocomplete="off"
          <?php if ($item['grade'] === 'do_nothing'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              現状品
            </label>

          </div>

        </td>
      </tr>

      </tbody>
    </table>
  </td>
</tr>
