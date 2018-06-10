<div id="page-wrapper">
  <!-- container-fluid : s -->
  <div class="container-fluid">
    <div class="page-header">
			<h1>딜러 - 안심 딜러 표기 관리</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mb20 mt20">

    <!-- search : s -->
    <div class="table-responsive">
      <form name="form1" id="form1" method="post">
        <table class="search_table">
          <tbody>
            <tr>
              <th width="150">등록일자</th>
              <td colspan="3">
                <input name="ins_s_date" id="ins_s_date" class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="ins_e_date" id="ins_e_date" class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
            </tr>
            <tr>
              <th width="150">게시시작일자</th>
              <td>
                <input name="start_s_date" id="start_s_date" class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="start_e_date" id="start_e_date"class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
              <th width="150">게시종료일자</th>
              <td>
                <input name="end_s_date" id="end_s_date" class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                <input name="end_e_date" id="end_e_date" class="form-control datepicker" style="width:150px" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
              </td>
            </tr>
            <tr>
              <th>검색어</th>
              <td colspan="3"><input type="text" name="search_text" id="search_text" class="form-control" placeholder="딜러명"> </td>
            </tr>
          </tbody>
        </table>
      </form>
      <div class="text-center mt20">
        <a href="javascript:relieved_dealer_list_get()" class="btn btn-success" id="btn_search" onclick="this.href">검색</a>
      </div>
    </div>
    <!-- search : e -->

    <!-- list : s -->
    <div class="table-responsive" id="result_list">
    </div>
    <!-- list : e -->

  </div>
  <!-- body : e -->

</div>
<!-- container-fluid : e -->

<script>

  $(document).ready(function(){

      $("#start_s_date").datepicker({
        yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#start_e_date").datepicker("option", "minDate", selectedDate);
      }
    });

    //안심 딜러 게시
    $("#start_e_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#start_s_date").datepicker("option", "maxDate", selectedDate);
      }
    });

    $("#end_s_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#end_e_date").datepicker("option", "minDate", selectedDate);
      }
    });

    $("#end_e_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#end_s_date").datepicker("option", "maxDate", selectedDate);
      }
    });

    $("#ins_s_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#ins_s_date").datepicker("option", "maxDate", selectedDate);
      }
    });

    $("#ins_e_date").datepicker({
      yearRange: '1900:+10',
      defaultDate: "+0w",
      dateFormat: "yy-mm-dd",
      prevText: '이전 달',
      nextText: '다음 달',
      monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
      dayNames: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
      dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
      showMonthAfterYear: true,
      changeMonth: true,
      changeYear: true,
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#ins_e_date").datepicker("option", "maxDate", selectedDate);
      }
    });

  });

  // 안심딜러 상태변경
  var relieved_dealer_type_up = function(member_idx, relieved_dealer_type){

    $.ajax({
      url: "/relieved_dealer/relieved_dealer_type_up",
      type: 'POST',
      dataType: 'json',
      async: true,
      data: {
        "member_idx": member_idx,
        "relieved_dealer_type_": relieved_dealer_type
      },
      success: function(dom){
        // 0:실패 1:성공 -1:이상
        if(dom.code == 0){
          alert(dom.msg);
        }else if(dom.code == 1) {
          relieved_dealer_list_get();
        }else{
          alert(dom.msg);
          location.href = "/relieved_dealer";
        }
      }
    });
  }

  //안심 딜러 리스트 조회
  var relieved_dealer_list_get = function(page_num) {

    if(page_num === null || page_num === undefined || page_num === "") {
      page_num = "1";
    }

    var sort_category = $("#sort_category").val();
    var formData = $("#form1").serializeArray();

    formData.push({name: "page_num", value: page_num});
    formData.push({name: "sort_category", value: sort_category});

    $.ajax({
      url: "/relieved_dealer/relieved_dealer_list_get",
      type: 'POST',
      dataType: 'html',
      async: true,
      data: formData,
      success: function(dom){
        $('#result_list').html(dom);
        document.location.hash = "#" + page_num;
      }
    });
  }

  relieved_dealer_list_get();
</script>
