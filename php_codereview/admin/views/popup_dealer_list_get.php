<div class="row table_title">
  <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;검색결과</div>
  <p class="col-lg-6 text-right">총 딜러회원 : <strong><?=$no?></strong> 명
    <select name="sort_category" id="sort_category" class="form-control ml10" style="width:120px" onchange="dealer_list_get()">
      <option value="">선택</option>
      <option value="member_name">이름</option>
      <option value="member_id">ID</option>
      <option value="member_phone">Phone</option>
      <option value="ins_date">가입일</option>
      <option value="">이용권 구매</option>
      <option value="work_place">소속</option>
      <option value="member_gender">성별</option>
      <option value="member_birth">연령</option>
      <option value="work_place_addr">지역</option>
      <option value="member_accept_yn">승인여부</option>
      <option value="del_yn">탈퇴여부</option>
    </select>
  </p>
</div>
<!-- list_get : s -->
<table class="table table-bordered">
  <thead>
    <tr>
      <th width="50">No</th>
      <th width="100">이름</th>
      <th width="*">ID</th>
      <th width="150">Phone</th>
      <th width="100">가입일</th>
      <th width="80">이용권<br>구매</th>
      <th width="100">소속</th>
      <th width="70">성별</th>
      <th width="70">연령</th>
      <th width="100">지역</th>
      <th width="100">승인여부</th>
      <th width="100">탈퇴여부</th>
      <th width="100">선택</th>
    </tr>
  </thead>
  <tbody>
    <?php
			if(!empty($result_list)) {
    		foreach ($result_list as $row) {
    ?>
          <tr>
            <td>
              <?=$no--?>
            </td>
            <td>
              <?=$row->member_name?>
            </td>
            <td>
              <?=$row->member_id?>
            </td>
            <td>
              <?=$this->global_function->set_phone_number($row->member_phone);?>
            </td>
            <td>
              <?=$this->global_function->dateYmdHyphen($row->ins_date);?>
            </td>
            <td>
              <?php
                echo $this->global_function->dateYmdHyphen($row->ticket_end_date) ;
                if($this->global_function->dateYmdHyphen($row->ticket_end_date) >= date("Y-m-d",time())) {
                  echo "유";
                } else if($this->global_function->dateYmdHyphen($row->ticket_end_date) < date("Y-m-d",time())) {
                  echo "무";
                }
              ?>
            </td>
            <td>
              <?=$row->work_place?>
            </td>
            <td>
              <?php
                switch($row->member_gender) {
                  case "0":
                    echo "남";
                    break;
                  case "1":
                    echo "여";
                    break;
                }
              ?>
            </td>
            <td>
              <?=$row->age?>세
            </td>
            <td>
              <?= $row->area_name ?>
            </td>
            <td>
              <?php
                switch($row->member_accept_yn) {
                  case "N":
                    echo "미승인";
                    break;
                  case "Y":
                    echo "승인";
                    break;
                }
              ?>
            </td>
            <td>
              <?php
                switch($row->del_yn) {
                  case "N":
                    echo "사용중";
                    break;
                  case "Y":
                    echo "탈퇴";
                    break;
                }
              ?>
            </td>
            <td>
              <a href="#" class="btn btn-success" onclick="javascript:dealer_choise('<?=$row->member_idx?>','<?=$row->member_name?>');">선택</a>
            </td>
          </tr>
    <?php
        }
      }else{
    ?>
        <tr>
          <td colspan="12">
            <h3>...</h3>
          </td>
        </tr>
    <?php
      }
    ?>
  </tbody>
</table>

<div class="row">
  <div class="col-lg-12">
    <?=$paging?>
  </div>

  <div class="col-lg-12 mt15 text-right">
    <a href="/member/dealer_reg" class="btn btn-success">등록</a>
  </div>
  <!-- list_get : e -->
</div>
