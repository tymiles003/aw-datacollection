<?php
$show_actions_enketo_data_collection = FALSE;
if (has_permission('enketo collect data any')) {
  $show_actions_enketo_data_collection = TRUE;
}
else if (has_permission('enketo collect data assigned') && $survey->is_assigned_agent(current_user()->uid)){
  $show_actions_enketo_data_collection = TRUE;
}
?>
<main id="site-body">
  <section class="row">
    <header id="page-head">
      <div class="inner">

        <div class="heading">
          <h1 class="hd-xl <?= $survey->get_status_html_class('indicator-'); ?>" title="<?= $survey->get_status_label() ?> - <?= $survey->title ?>"><?= $survey->title ?></h1>
        </div>

        <nav id="secondary" role="navigation">
          <ul class="bttn-toolbar">
            <li class="sector-switcher">
              <a class="bttn-sector bttn-dropdown" href="#" data-dropdown="action-bttn"><strong>Respondents</strong></a>
              <ul class="action-dropdown">
                <li><a href="<?= $survey->get_url_view() ?>">Summary</a></li>
                
                <?php if ($show_actions_enketo_data_collection) : ?>
                <?php $disabled = !$survey->has_xml() || !$survey->status_allows('view call activity') ? 'disabled' : ''; ?>
                <li><a href="<?= $survey->get_url_call_activity() ?>" class="<?= $disabled ?>">Call activity</a></li>
                <?php endif; ?>
              </ul>
            </li>

            <?php if (has_permission('manage respondents any survey')) : ?>
            <li>
              <?php $class = 'bttn bttn-primary bttn-medium bttn-dropdown bttn-icon-add'; ?>
              <?php $class .= !$survey->status_allows('import respondents any survey') ? ' disabled': ''; ?>
              <a href="#" class="<?= $class; ?>" data-dropdown="action-bttn">Add new</a>
              <ul class="action-dropdown">
                <li><a href="<?= $survey->get_url_respondents_add('file'); ?>">Upload file</a></li>
                <li><a href="<?= $survey->get_url_respondents_add('direct'); ?>">Direct input</a></li>
              </ul>
            </li>
            <?php endif; ?>

          </ul>
        </nav>

      </div>
    </header>
    <div class="content">
      <div class="columns small-12">
        
        <section class="contained">
          <?php if (empty($respondents)) : ?>
            
            <div class="contained-empty">
              <h1>Nothing to show</h1>
              <p>Click the <em>Add new</em> button to import respondents.</p>
            </div>
            
          <?php else : ?>
            <header class="contained-head">
              <h1 class="hd-s visually-hidden">Respondents</h1>
              <ul class="bttn-toolbar">
                <li>
                  <a href="#" class="bttn bttn-default bttn-small bttn-dropdown bttn-icon-edit" data-dropdown="action-bttn">Bulk edit</a>
                  <ul class="action-dropdown for-bttn-small">
                    <?php $class = 'danger'; ?>
                    <?php $class .= !$survey->status_allows('delete respondents any survey') ? ' disabled': ''; ?>
                    <li><a href="#" class="<?= $class; ?>" data-trigger-submit="respondent-delete" data-confirm-action="Are you sure?">Delete</a></li>
                  </ul>
                </li>
              </ul>
            </header>
  
            <div class="contained-body">
              <?= form_open($survey->get_url_respondents_manage_bulk()); ?>
              <table class="fancy-cb-group">
                <thead>
                  <tr>
                    <th>
                      <label class="fancy-cb-label cb-master-label" for="respondents-check-all">
                        <input name="respondents-check-all" value="1" type="checkbox" class="cb-master"/>
                      </label>
                      <span class="fancy-cb-count" data-count-all="<?= $this->pagination->total_rows; ?>">0</span>
                    </th>
                    <th>Number</th>
                    <th>Date</th>
                    <th>Activity</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($respondents as $resp) : ?>
                  <tr>
                    <td>
                      <label class="fancy-cb-label cb-slave-label" for="respondents-check">
                        <input name="respondents-check[]" value="<?= $resp->ctid; ?>" type="checkbox" class="cb-slave"/>
                      </label>
                    </td>
                    <td><strong class="highlight"><?= $resp->number != NULL ? $resp->number : 'Hidden'; ?></strong></td>
                    <td><?= date('d M, Y', $resp->updated->sec) ?> <small><?= $resp->created == $resp->updated ? 'Created' : 'Updated'; ?></small></td>
                    <td><?= empty($resp->activity) ? 'No' : 'Yes' ?></td>
                    <td>
                      <ul class="bttn-toolbar">
                        <li>
                          <a href="#" class="bttn bttn-primary bttn-small bttn-dropdown bttn-icon-edit" data-dropdown="action-bttn">Edit</a>
                          <ul class="action-dropdown for-bttn-small">
                            <?php
                              $class = 'danger';
                              $activity = $resp->get_activity();
                              if (!empty($activity) || !$survey->status_allows('delete respondents any survey')) {
                                $class .= ' disabled';
                              }
                            ?>
                            <li><?= anchor_csrf($resp->get_url_delete(), 'Delete', array('class' => $class, 'data-confirm-action' => 'Are you sure?')) ?></li>
                          </ul>
                        </li>
                      </ul>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
              <?= form_button(array(
                'type' => 'submit',
                'name' => 'respondent-delete',
                'id' => 'respondent-delete',
                'value' => 'respondent-delete',
                'class' => 'hide',
                'content' => 'Delete'));
              ?>
              <?= form_close(); ?>
          </div>
          <footer class="contained-foot">
            <?php $pagination = $this->pagination->create_links(); ?>
            <p><?= $this->pagination->get_page_info(); ?></p>
            <?= $pagination; ?>
          </footer>
          
        <?php endif; ?>
        
        </section>
           
      </div>
    </div>
  </section>
</main>