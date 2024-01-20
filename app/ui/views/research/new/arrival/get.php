<?php $this->setPageTitle('title', 'ストア新着作成') ?>

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
  <li>ストア新着</li>

  <li class="active">

  <?php if (isset($research_new_arrival['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $research_new_arrival['id']
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

    <?php print $this->render('research/new/arrival/crudd',
                  array(
                    'param'  => $research_new_arrival)); ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">
            <p>タイトル</p>
          </span>
        </th>
        <td class="active">
          <input type="text"
                 name="name"
                 value="<?php print $research_new_arrival['name'] ?>"
                 size="96" />
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">アクション</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_new_arrival['action'] === 'chatwork'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="chatwork"
                     autocomplete="off"
          <?php if ($research_new_arrival['action'] === 'chatwork'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              ChatWorkへ通知
            </label>

          <?php if ($research_new_arrival['action'] === 'database'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="database"
                     autocomplete="off"
          <?php if ($research_new_arrival['action'] === 'database'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              データベースへ登録
            </label>


          <?php if ($research_new_arrival['action'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="all"
                     autocomplete="off"
          <?php if ($research_new_arrival['action'] === 'all'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              すべて実行する
            </label>

          <?php if ($research_new_arrival['action'] === 'do_nothing'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="action"
                     value="do_nothing"
                     autocomplete="off"
          <?php if ($research_new_arrival['action'] === 'do_nothing'): ?>
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

          <?php if ($research_new_arrival['chatwork_to'] === 'grant'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="chatwork_to"
                     value="grant"
                     autocomplete="off"
          <?php if ($research_new_arrival['chatwork_to'] === 'grant'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              付与する
            </label>

          <?php if ($research_new_arrival['chatwork_to'] === 'do_not_grant'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="chatwork_to"
                     value="do_not_grant"
                     autocomplete="off"
          <?php if ($research_new_arrival['chatwork_to'] === 'do_not_grant'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              付与しない
            </label>

          </div>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">在庫</span>
        </th>

        <td class="active">

          <div class="btn-group" data-toggle="buttons">

          <?php if ($research_new_arrival['stock'] === 'existence'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="stock"
                     value="existence"
                     autocomplete="off"
          <?php if ($research_new_arrival['chatwork_to'] === 'existence'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              あり
            </label>

          <?php if ($research_new_arrival['stock'] === 'not_existence'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="stock"
                     value="not_existence"
                     autocomplete="off"
          <?php if ($research_new_arrival['stock'] === 'not_existence'): ?>
                     checked />
          <?php else: ?>
                     />
          <?php endif; ?>

              なし
            </label>

          <?php if ($research_new_arrival['stock'] === 'all'): ?>

            <label class="btn btn-default active"
          <?php else: ?>

            <label class="btn btn-default"
          <?php endif; ?>
                   style="width: 160px">

              <input type="radio"
                     name="stock"
                     value="all"
                     autocomplete="off"
          <?php if ($research_new_arrival['stock'] === 'all'): ?>
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
            商品タイトル
          </span>
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
                         id="title_include_everything"
                         name="title_include_everything"
                         value="<?php print $research_new_arrival['title_include_everything'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">いずれかを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="title_include_either"
                         name="title_include_either"
                         value="<?php print $research_new_arrival['title_include_either'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">含めない</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="title_not_include"
                         name="title_not_include"
                         value="<?php print $research_new_arrival['title_not_include'] ?>"
                         size="80" />
                </td>
              </tr>

            </tbody>
          </table>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品ランク</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/kitamura',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/fujiya_camera',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/camera_no_naniwa',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/map_camera',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/champ_camera',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            <?php
              print $this->render(
                'research/new/arrival/checkbox/hardoff',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

            </tbody>

          </table>
        </td>
      </tr>

<!--
              <tr>
                <th class="text-center info">
                  <span class="text-primary">付属品</span>
                </th>

                <td class="text-left active">
                  <input type="text"
                         name="accessories"
                         value="<?php /* print $research_new_arrival['accessories'] */ ?>"
                         size="64" />
                </td>
              </tr>
-->

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>商品状態（備考欄）</p>
                    <p>（クモリ、カビ、などの商品状態で絞り込む）</p>
                    <p>（対応ストア：カメラのキタムラ／カメラのナニワ）</p>
                  </span>
                </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">いずれかを含む</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="remarks_include_either"
                         name="remarks_include_either"
                         value="<?php print $research_new_arrival['remarks_include_either'] ?>"
                         size="80" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">含めない</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         id="remarks_not_include"
                         name="remarks_not_include"
                         value="<?php print $research_new_arrival['remarks_not_include'] ?>"
                         size="80" />
                </td>
              </tr>

            </tbody>
          </table>

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
                  <span class="text-primary">店舗価格</span>
                </th>
                <td class="text-left active">

                  <?php print $research_new_arrival['store_price'] ?>円

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">下限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="min_price"
                         value="<?php print $research_new_arrival['min_price'] ?>"
                         size="8" />
                  円
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">上限値</span>
                </th>
                <td class="text-left active">
                  <input type="text"
                         name="max_price"
                         value="<?php print $research_new_arrival['max_price'] ?>"
                         size="8" />
                  円
                </td>
              </tr>

            </tbody>
          </table>
        </td>

      <tr>
        <th class="text-center info">
          <span class="text-primary">管理ID</span>
       </th>

        <td class="active"><?php print $research_new_arrival['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
       </th>

        <td class="active"><?php print $research_new_arrival['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $research_new_arrival['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="store_price" value="<?php print $research_new_arrival['store_price'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $research_new_arrival['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $research_new_arrival['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $research_new_arrival['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

