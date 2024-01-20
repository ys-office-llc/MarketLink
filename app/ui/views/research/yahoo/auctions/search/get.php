<?php $this->setPageTitle('title', 'ヤフオク検索作成') ?>

<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<ol class="breadcrumb">

  <li>

    <a href="<?php print $this->potal().$base_url; ?>">

      ポータル

    </a>
  </li>
  <li>相場スクリーニング</li>
  <li>ヤフオク検索</li>

  <li class="active">

  <?php if (isset($research_yahoo_auctions_search['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $research_yahoo_auctions_search['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      作成

    </a>

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php

      print $this->render(
        'research/yahoo/auctions/search/crud',
        array(
          'param'      => $research_yahoo_auctions_search,
          'go_update'  => $go_update,
          'search_url' => $search_url,
        )
      )

    ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">開催中のオークション</span>
       </th>

        <td class="active">

          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">検索結果</span>
                </th>

                <td class="active" style="width: 600px">

                  <strong>

                    約<?php print $search_count ?>件

                  </strong>

               </td>
             </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">詳細</span>
                </th>

                <td class="active" style="width: 600px">

        <?php  if ((int)$search_count > 0): ?>

          <a class="btn btn-primary"
             href="<?php print $search_url ?>"
             target="_blank">

            出品商品を見る

          </a>

        <?php  endif; ?>

               </td>
             </tr>


            </tbody>
          </table>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">タイトル</span>
       </th>
        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $research_yahoo_auctions_search['name'] ?>"
                 size="80" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">アクション</span>
        </th>

        <td class="active">
          
          <div class="btn-group" data-toggle="buttons">
          
          <?php if ($research_yahoo_auctions_search['action'] === 'chatwork'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">
          
              <input type="radio"
                     name="action"
                     value="chatwork"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['action'] === 'chatwork'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>
          
              ChatWorkへ通知
            </label>

          <?php if ($research_yahoo_auctions_search['action'] === 'watchlist'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="watchlist"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['action'] === 'watchlist'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              ウォッチリストへ登録
            </label>

          <?php if ($research_yahoo_auctions_search['action'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['action'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて実行する
            </label>

          <?php if ($research_yahoo_auctions_search['action'] === 'do_nothing'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="do_nothing"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['action'] === 'do_nothing'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              何もしない
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ChatWork宛先</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_yahoo_auctions_search['chatwork_to'] === 'grant'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="chatwork_to"
                     value="grant"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['chatwork_to'] === 'grant'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              付与する
            </label>

          <?php if ($research_yahoo_auctions_search['chatwork_to'] === 'do_not_grant'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="chatwork_to"
                     value="do_not_grant"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['chatwork_to'] === 'do_not_grant'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              付与しない
            </label>

          </div>

        </td>
      </tr>

<?php if ($this->getUserData()['account_authority_level_id'] > 1): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">無在庫出品</span>
        </th>

        <td class="active">

          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>

                <th class="text-center info">
                  <span class="text-primary">スイッチ</span>
                </th>

                <td class="text-left active">

                  <div class="btn-group" data-toggle="buttons">

                  <?php if ($research_yahoo_auctions_search['stockless'] === 'enable'): ?>

                    <label class="btn btn-default active"
                  <?php else: ?>

                    <label class="btn btn-default"
                  <?php endif; ?>
                           style="width: 160px">

                      <input type="radio"
                             name="stockless"
                             value="enable"
                             autocomplete="off"
                  <?php if ($research_yahoo_auctions_search['stockless'] === 'enable'): ?>
                             checked />
                  <?php else: ?>
                             />
                  <?php endif; ?>
        
                      有効
                    </label>

                  <?php if ($research_yahoo_auctions_search['stockless'] === 'disable'): ?>

                    <label class="btn btn-default active"
                  <?php else: ?>

                    <label class="btn btn-default"
                  <?php endif; ?>
                           style="width: 160px">

                      <input type="radio"
                             name="stockless"
                             value="disable"
                             autocomplete="off"
                  <?php if ($research_yahoo_auctions_search['stockless'] === 'disable'): ?>
                             checked />
                  <?php else: ?>
                             />
                  <?php endif; ?>

                      無効
                    </label>
        
                  </div>

                </td>
              </tr>

              <tr>
                <th class="text-center info">

                  <span class="text-primary">
                    <p>除外する画像のURL</p>
                    <p>（空白（改行含む）区切り）</p>

                  </span>
                </th>

                <td class="text-left active">
                  <textarea name="except_img_urls"
                            rows="5"
                            cols="80"><?php print $research_yahoo_auctions_search['except_img_urls'] ?></textarea>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                <?php if ($research_yahoo_auctions_search['my_pattern_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/my/pattern/get/<?php print $research_yahoo_auctions_search['my_pattern_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                     <span class="text-default">マイパターン</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">マイパターン</span>
                <?php endif; ?>
                </th>
                <td class="active">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'my_pattern_id',
                      'values'   => $item_table_values['item_my_pattern'],
                      'selected' => $research_yahoo_auctions_search['my_pattern_id'],
                      'sort'     => 'asort',
                    )); ?>
                </td>
              </tr>
        
            </tbody>
          </table>
        </td>
      </tr>

<?php endif; ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">検索キーワード</span>
       </th>
        <td class="active">

          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">すべてを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         name="query_include_everything"
                         value="<?php print $research_yahoo_auctions_search['query_include_everything'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">いずれかを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         name="query_include_either"
                         value="<?php print $research_yahoo_auctions_search['query_include_either'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">含めない</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         name="query_not_include"
                         value="<?php print $research_yahoo_auctions_search['query_not_include'] ?>"
                         size="80" />
                </td>
              </tr>

            </tbody>
          </table>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">カテゴリー</span>
        </th>

        <td class="active" style="width: 900px">
          <?php print $this->render('select',
            array(
              'name'     => 'category_id',
              'values'   => $table_values['research_yahoo_auctions_search_category'],
              'selected' => $research_yahoo_auctions_search['category_id'],
              'sort'     => 'asort',
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
          <span class="text-primary">検索対象</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">


          <?php if ($research_yahoo_auctions_search['search_target'] === 'title_only'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 150px">

              <input type="radio"
                     name="search_target"
                     value="title_only"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['search_target'] === 'title_only'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              タイトル
            </label>

          <?php if ($research_yahoo_auctions_search['search_target'] === 'title_description'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 150px">

              <input type="radio"
                     name="search_target"
                     value="title_description"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['search_target'] === 'title_description'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              タイトルと商品説明
            </label>

          <?php if ($research_yahoo_auctions_search['search_target'] === 'all'): ?>
            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 150px">

              <input type="radio"
                     name="search_target"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['search_target'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">入札数</span>
        </th>

        <td class="active">
          <input type="text"
                 name="aucminbids"
                 value="<?php print $research_yahoo_auctions_search['aucminbids'] ?>"
                 size="8" />&nbsp;件
          ～
          <input type="text"
                 name="aucmaxbids"
                 value="<?php print $research_yahoo_auctions_search['aucmaxbids'] ?>"
                 size="8" />&nbsp;件
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品価格</span>
        </th>

        <td class="active">
          <input type="text"
                 name="aucminprice"
                 value="<?php print $research_yahoo_auctions_search['aucminprice'] ?>"
                 size="8" />&nbsp;円
          ～
          <input type="text"
                 name="aucmaxprice"
                 value="<?php print $research_yahoo_auctions_search['aucmaxprice'] ?>"
                 size="8" />&nbsp;円
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">即決価格</span>
        </th>

        <td class="active">
          <input type="text"
                 name="aucmin_bidorbuy_price"
                 value="<?php print $research_yahoo_auctions_search['aucmin_bidorbuy_price'] ?>"
                 size="8" />&nbsp;円
          ～
          <input type="text"
                 name="aucmax_bidorbuy_price"
                 value="<?php print $research_yahoo_auctions_search['aucmax_bidorbuy_price'] ?>"
                 size="8" />&nbsp;円
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品状態</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_yahoo_auctions_search['item_status'] === 'second_hand'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="item_status"
                     value="second_hand"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['item_status'] === 'second_hand'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              中古
            </label>

          <?php if ($research_yahoo_auctions_search['item_status'] === 'brand_new'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="item_status"
                     value="brand_new"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['item_status'] === 'brand_new'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              新品
            </label>

          <?php if ($research_yahoo_auctions_search['item_status'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="item_status"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['item_status'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">出品区分</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_yahoo_auctions_search['listing_category'] === 'store'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="listing_category"
                     value="store"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['listing_category'] === 'store'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              ストア
            </label>

          <?php if ($research_yahoo_auctions_search['listing_category'] === 'general'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="listing_category"
                     value="general"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['listing_category'] === 'general'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              一般
            </label>

          <?php if ($research_yahoo_auctions_search['listing_category'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="listing_category"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['listing_category'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">自動延長</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'true'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="is_automatic_extension"
                     value="true"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'true'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              あり
            </label>

          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'false'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="is_automatic_extension"
                     value="false"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'false'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              なし
            </label>

          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="is_automatic_extension"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['is_automatic_extension'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">最低落札価格</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_yahoo_auctions_search['reserved'] === 'true'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="reserved"
                     value="true"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['reserved'] === 'true'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              あり
            </label>

          <?php if ($research_yahoo_auctions_search['reserved'] === 'false'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="reserved"
                     value="false"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['reserved'] === 'false'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              なし
            </label>

          <?php if ($research_yahoo_auctions_search['reserved'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 80px">

              <input type="radio"
                     name="reserved"
                     value="all"
                     autocomplete="off"
          <?php if ($research_yahoo_auctions_search['reserved'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて
            </label>

          </div>

        </td>
      </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>絞り込む出品者</p>
                    <p>（カンマ区切り）</p>
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
                    <p>除外する出品者</p>
                    <p>（カンマ区切り）</p>
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

