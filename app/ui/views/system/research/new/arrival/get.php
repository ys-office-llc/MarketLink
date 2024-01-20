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
                    'param'  => $research_new_arrival)); ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">
            識別名称
          </span>
        </th>
        <td class="active">
          <input type="text"
                 name="name"
                 value="<?php print $research_new_arrival['name'] ?>"
                 size="32" />
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">
            絞り込みワード
          </span>
        </th>
        <td class="active">
          <input type="text"
                 name="query"
                 value="<?php print $research_new_arrival['query'] ?>"
                 size="80" />
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
                'research/new/arrival/checkbox/map_camera',
                array(
                  'research_new_arrival' => $research_new_arrival
              )); ?>

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
                  <span class="text-primary">付属品</span>
                </th>

                <td class="text-left active">
                  <input type="text"
                         name="accessories"
                         value="<?php print $research_new_arrival['accessories'] ?>"
                         size="64" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">
                    <p>備考の特定文字列を除外する</p>
                    <p>（コンマ（,）区切りで複数指定可能）</p>
                  </span>
                </th>

                <td class="text-left active">
                  <textarea name="remarks_except"
                            rows="3"
                            cols="80"><?php print $research_new_arrival['remarks_except'] ?></textarea>
                </td>
              </tr>

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
  <input type="hidden" name="created_at" value="<?php print $research_new_arrival['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $research_new_arrival['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $research_new_arrival['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

