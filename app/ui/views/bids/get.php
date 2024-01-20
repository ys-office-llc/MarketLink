<?php $this->setPageTitle('title', $research_watch_list['title']) ?>
<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>
<?php print $this->render('errors', array('errors' => $errors)); ?>
<?php endif; ?>
<?php if (isset($successes) and count($successes) > 0): ?>
<?php print $this->render('successes', array('successes' => $successes)); ?>
<?php endif; ?>

<form action="<?php print $base_url; ?>/bids/post"
      class="repeater"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('bids/crud',
                  array(
                    'param'    => $bids,
                    'to_close' => $to_close
                  )); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered table-condensed">

    <tbody>

    <?php if ($bids['state_id'] > 0): ?>
      <tr>
        <th class="text-center info">
          <span class="text-primary">状態</span>
        </th>

        <td class="active" style="width: 800px">
          <div class="progress">
            <div class="progress-bar"
                 aria-valuenow="25"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width:<?php print $bids['state_id'] * 25 ?>%">

              <?php print $table_values['bids_state'][$bids['state_id']]['name'] ?>
            </div>
          </div>
        </td>
      </tr>
    <?php endif; ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <td class="active">
          <a href="<?php print $research_watch_list['auction_item_url'] ?>"
             target="_blank">

          <?php print $research_watch_list['title'] ?>

          </a>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">画像</span>
        </th>

        <td class="text-left active" style="width: 800px">
          <?php print $this->render('bids/modal',
                               array(
                                 'research_watch_list' => $research_watch_list
                               )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">出品者</span>
        </th>

        <td class="text-left active" style="width: 800px">

          <a href="http://sellinglist.auctions.yahoo.co.jp/user/<?php print $research_watch_list['seller_id'] ?>"
             target="_blank">

            <?php print $research_watch_list['seller_id'] ?>

          </a>
          （評価：<?php print $research_watch_list['rating_point'] ?> 良い：<?php print $research_watch_list['rating_total_good_rating'] ?> 悪い：<a href="http://auctions.yahoo.co.jp/jp/show/rating?userID=<?php print $research_watch_list['seller_id'] ?>&filter=-1#comment_list" target="_blank"><?php print $research_watch_list['rating_total_bad_rating'] ?>）
          </a>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">価格</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">現在</span>
                </th>

                <td class="active" style="width: 650px">
                  <?php print number_format(
                          $research_watch_list['current_price']
                        ) ?>円
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">入札価格</span>
                </th>

                <td class="active" style="width: 650px">
                  <?php print number_format($bids['bids_price']) ?>円
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">管理ID</span>
        </th>

        <td class="active"><?php print $bids['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
        </th>

        <td class="active"><?php print $bids['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
        </th>

        <td class="active"><?php print $bids['modified_at'] ?></td>
      </tr>

    </tbody>
  </table>
  </div>
</table>
</div>
  <input type="hidden" name="id" value="<?php print $bids['id'] ?>" />
  <input type="hidden" name="research_watch_list_id" value="<?php print $research_watch_list['id'] ?>" />
  <input type="hidden" name="bids_price" value="<?php print $bids['bids_price'] ?>" />
  <input type="hidden" name="user_id" value="<?php print $bids['user_id'] ?>" />
  <input type="hidden" name="state_id" value="<?php print $bids['state_id'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $bids['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $bids['modified_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
