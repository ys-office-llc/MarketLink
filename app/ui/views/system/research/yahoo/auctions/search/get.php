<?php $this->setPageTitle('title', 'ヤフオク検索条件作成') ?>
<?php print $this->render('nav', array('' => array())); ?>
<?php if (isset($errors) and count($errors) > 0): ?>
<?php print $this->render('errors', array('errors' => $errors)); ?>
<?php endif; ?>
<?php if (isset($successes) and count($successes) > 0): ?>
<?php print $this->render('successes', array('successes' => $successes)); ?>
<?php endif; ?>
<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crud',
                  array(
                    'param'  => $research_yahoo_auctions_search)); ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">検索キーワード（必須）</span>
       </th>
        <td class="active">
          <input type="text"
                 name="query"
                 value="<?php print $research_yahoo_auctions_search['query'] ?>"
                 size="80" />
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">実行メソッド</span>
       </th>

       <td class="active" style="width: 800px">
         <?php print $this->render('select',
           array(
             'name'     => 'method_id',
             'values'   => $table_values['research_yahoo_auctions_search_method'],
             'selected' => $research_yahoo_auctions_search['method_id'],
           )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">絞り込み条件（オプション）</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">指定検索の種類</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'type_id',
              'values'   => $table_values['research_yahoo_auctions_search_type'],
              'selected' => $research_yahoo_auctions_search['type_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">カテゴリー</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'category_id',
              'values'   => $table_values['research_yahoo_auctions_search_category'],
              'selected' => $research_yahoo_auctions_search['category_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ソート項目</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'sort_id',
              'values'   => $table_values['research_yahoo_auctions_search_sort'],
              'selected' => $research_yahoo_auctions_search['sort_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ソートの順番</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'order_id',
              'values'   => $table_values['research_yahoo_auctions_search_order'],
              'selected' => $research_yahoo_auctions_search['order_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品の出品区分絞り込み</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'store_id',
              'values'   => $table_values['research_yahoo_auctions_search_store'],
              'selected' => $research_yahoo_auctions_search['store_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">入札数の範囲指定</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">下限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="aucminbids"
                         value="<?php print $research_yahoo_auctions_search['aucminbids'] ?>"
                         size="8" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">上限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="aucmaxbids"
                         value="<?php print $research_yahoo_auctions_search['aucmaxbids'] ?>"
                         size="8" />
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品価格の範囲指定</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">下限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="aucminprice"
                         value="<?php print $research_yahoo_auctions_search['aucminprice'] ?>"
                         size="8" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">上限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="aucmaxprice"
                         value="<?php print $research_yahoo_auctions_search['aucmaxprice'] ?>"
                         size="8" />
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">即決価格の範囲指定</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">下限値</span>
                </th>

                <td class="text-left active">
                  <input type="text"
                         name="aucmin_bidorbuy_price"
                         value="<?php print $research_yahoo_auctions_search['aucmin_bidorbuy_price'] ?>"
                         size="8" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">上限値</span>
                </th>

                <td class="text-left active">
                  <input type="text"
                         name="aucmax_bidorbuy_price"
                         value="<?php print $research_yahoo_auctions_search['aucmax_bidorbuy_price'] ?>"
                         size="8" />
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">指定された残り時間（秒）を除外</span>
                </th>

                <td class="text-left active">
                  <input type="text"
                         name="timebuf"
                         value="<?php print $research_yahoo_auctions_search['timebuf'] ?>"
                         size="8" />
                </td>
              </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品状態での絞り込み</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'item_status_id',
              'values'   => $table_values['research_yahoo_auctions_search_item_status'],
              'selected' => $research_yahoo_auctions_search['item_status_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">検索対象の指定</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'f_id',
              'values'   => $table_values['research_yahoo_auctions_search_f'],
              'selected' => $research_yahoo_auctions_search['f_id'],
            )); ?>
        </td>
      </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>出品者のYahoo! JAPAN IDで絞り込み</p>
                    <p>（コンマ（,）区切りで複数指定可能）</p>
                  </span>
                </th>

                <td class="text-left active">
                  <textarea name="seller"
                            rows="3"
                            cols="80"><?php print $research_yahoo_auctions_search['seller'] ?></textarea>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>出品者のYahoo! JAPAN IDを除外する</p>
                    <p>（コンマ（,）区切りで複数指定可能）</p>
                  </span>
                </th>

                <td class="text-left active">
                  <textarea name="seller_except"
                            rows="3"
                            cols="80"><?php print $research_yahoo_auctions_search['seller_except'] ?></textarea>
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

        <td class="active"><?php print $research_yahoo_auctions_search['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
       </th>

        <td class="active"><?php print $research_yahoo_auctions_search['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $research_yahoo_auctions_search['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $research_yahoo_auctions_search['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $research_yahoo_auctions_search['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $research_yahoo_auctions_search['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

