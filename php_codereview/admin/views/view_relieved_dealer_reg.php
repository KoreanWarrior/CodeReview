<div id="page-wrapper">
  <div class="container-fluid">
    <!-- Page Heading -->
    <div class="page-header">
      <h1>딜러 - 안심 딜러 표기 관리</h1>
    </div>

    <!-- body : s -->
    <div class="bg_wh mb20 mt20">
    	<div class="table-responsive">
        <!-- top -->
        <div class="row table_title">
          <div class="col-lg-6"> &nbsp;<i class="fa fa-check" aria-hidden="true"></i> &nbsp;추가</div>
        </div>
        <!-- top  -->
        <form id="form1" >
        <section>
          <table class="table table-bordered td_left">
            <colgroup>
              <col style="width:100px">
              <col style="width:350px">
              <col style="width:100px">
              <col style="width:350px">
            </colgroup>
        		<tbody>
              <tr>
                <th>딜러명</th>
                <td>
                  <label for="member_name" id="member_name" value=""></label>  &nbsp; <a href="javascript:dealer_choise_list_popup()" onclick="this.href" class="btn-sm btn-success">딜러선택</a>
                  <input type="hidden" name="member_idx" id="member_idx" value="">
                </td>
                <th>분류</th>
                <td>
                  <label name="relieved_dealer_type" id="relieved_dealer_type">안심노출형</label>
                </td>
              </tr>
              <tr>
                <th>게시기간</th>
                <td>
                  <input name="relieved_dealer_start_date" id="relieved_dealer_start_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input name="relieved_dealer_end_date" id="relieved_dealer_end_date" class="form-control" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
                <th>딜러상태</th>
                <td>
                  <select class="form-control" name="relieved_display_yn">
                    <option value="Y">노출</option>
                    <!-- <option value="N">정지</option> -->
                    <option value="B">블라인드</option>
                  </select>
                </td>
              </tr>
            </tbody>
        	</table>
        </section>
        </form>

        <div class="row mt15">
          <div class="col-lg-12 text-right">
            <a href="/relieved_dealer" class="btn btn-gray">취소</a>
            <!-- 수정일 때 -->
            <!-- <a href="#" class="btn btn-danger">삭제</a> -->
            <a href="javascript:relieved_dealer_reg_in()" onclick="this.href" class="btn btn-success">저장</a>
          </div>
        </div>

    	</div>
    </div>
    <!-- body : e -->

  </div>
  <!-- container-fluid : e -->
<script>
  $(function() {

    $("#relieved_dealer_start_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
      monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      yearSuffix: '',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#relieved_dealer_end_date").datepicker("option", "minDate", selectedDate);

      }
    });
    $("#relieved_dealer_end_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
      monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      yearSuffix: '',
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#relieved_dealer_start_date").datepicker("option", "maxDate", selectedDate);
      }
    });

  });

  //딜러 선택 리스트 팝업
  var dealer_choise_list_popup = function(estimate_submit_idx) {
    var url = "/relieved_dealer/dealer_choise_list";
    window.open(url, 'CLIENT_WINDOW', 'resizable=no scrollbars=no width=1200 height=750');
  }

  //딜러-안심딜러 저장
  var relieved_dealer_reg_in = function(){

    if($('#member_idx').val() ==""){
      alert("딜러를 선택해주세요.");
      return FALSE;
    }

    if($('#relieved_dealer_start_date').val() ==""){
      alert("게시기간 시작일을 입력해주세요.");
      return FALSE;
    }

    if($('#relieved_dealer_end_date').val() ==""){
      alert("게시기간 종료일을 입력해주세요.");
      return FALSE;
    }

     $.ajax({
       url: "/relieved_dealer/relieved_dealer_reg_in",
       type: "post",
       data : $('#form1').serialize(),
       dataType: 'json',
       async: true,
       success: function(dom){
         // 0:실패 1:성공 -1:이상
         if(dom.code == 0){
           alert(dom.msg);
         }else if(dom.code == 1) {
           alert(dom.code_msg);
           location.href = "./relieved_dealer";
         }else{
           alert(dom.msg);
           location.href = "/relieved_dealer";
         }
       }
     });
  }
</script>
