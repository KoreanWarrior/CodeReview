<div>
  <div class="row table_title">
    <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과</div>
    <p class="col-lg-6 text-right">총 : <strong><?= $result_list_count ?></strong> 건
      <select name="sort_category" id="sort_category" class="form-control ml10" style="width:120px" onchange="relieved_dealer_list_get()">
        <option value="">선택</option>
        <option value="member_name">딜러명</option>
        <option value="relieved_dealer_start_date">게시기간</option>
        <option value="relieved_dealer_type">분류</option>
        <option value="relieved_display_yn">표기상태</option>
      </select>
    </p>
  </div>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th width="50">No</th>
        <th width="150">딜러명</th>
        <th width="200">게시기간</th>
        <th width="150">분류</th>
        <th width="100">표기상태</th>
        <th width="100"></th>
      </tr>
    </thead>
    <tbody>


      <?php
        if(!empty($result_list)){
          foreach($result_list as $row) { ?>
            <tr>
              <td>
                <?=$no--?>
              </td>
              <td>
                <a href="/relieved_dealer/relieved_dealer_view?member_idx=<?= $row->member_idx ?>"><?= $row->member_name ?></a>
              </td>
              <td>
                <?= date('Y-m-d',strtotime($row->relieved_dealer_start_date)) ?> ~ <?= date('Y-m-d',strtotime($row->relieved_dealer_end_date)) ?>
              </td>
              <td>
                <?php
                  switch($row->relieved_dealer_type) {
                    case "1" : echo "안심노출"; break;
                  }
                ?>
              </td>
              <td>
                <?php
                  switch($row->relieved_display_yn) {
                    case "Y" : echo "노출"; break;
                    case "N" : echo "정지"; break;
                    case "B" : echo "블라인드"; break;
                  }
                ?>
              </td>
              <td>
                <?php
                  if($row->relieved_dealer_type == "1") {
                ?>
                    <a href="javascript:relieved_dealer_type_up('<?= $row->member_idx ?>', '0')" class="btn btn-danger" onclick="this.href">삭제</a>
                <?php
                  }
                ?>
              </td>
            </tr>
      <?php
        }
      } else { ?>
        <td colspan="6"><h4>...</h4></td>
      <?php } ?>
    </tbody>
  </table>

<div class="row">
  <div class="col-lg-12">
    <?=$paging?>
  </div>

  <div class="col-lg-12 mt15 text-right">
    <a href="/relieved_dealer/relieved_dealer_reg" class="btn btn-success">등록</a>
  </div>
  <!-- list_get : e -->
</div>
