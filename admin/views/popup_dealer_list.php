<div id="page-wrapper">
  <div class="container-fluid">
  	<!-- Page Heading -->
    <div class="page-header">
			<h1>딜러 관리</h1>
		</div>

    <!-- body : s -->
    <div class="bg_wh mb20 mt20">
      <!-- search : s -->
      <div class="table-responsive">
        <form name="form1" id="form1">

          <table class="search_table">
            <tbody>
              <tr>
                <th width="150">가입일자</th>
                <td>
                  <input class="form-control datepicker" name="search_s_date" id="search_s_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input class="form-control datepicker" name="search_e_date" id="search_e_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
                <th width="150">생년월일</th>
                <td>
                  <input class="form-control datepicker" name="birth_s_date" id="birth_s_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>&nbsp;~&nbsp;
                  <input class="form-control datepicker" name="birth_e_date" id="birth_e_date" placeholder="">&nbsp;<i class="fa fa-calendar-o"></i>
                </td>
              </tr>
              <tr>
                <th>연령대</th>
                <td>
                  <select class="form-control ml10" style="width:120px" name="search_age" id="search_age">
                    <option value="">전체</option>
                    <option value="10">10대</option>
                    <option value="20">20대</option>
                    <option value="30">30대</option>
                    <option value="40">40대</option>
                    <option value="50">50대</option>
                    <option value="60">60대</option>
                    <option value="70">70대</option>
                  </select>
                </td>
                <th width="150">이용권 구매</th>
                <td>
                  <label class="radio-inline"><input type="radio" name="ticket_end_date" id="ticket_end_date_all" checked> 전체</label>
                  <label class="radio-inline"><input type="radio" name="ticket_end_date" id="ticket_end_date_y" value="1"> 유</label>
                  <label class="radio-inline"><input type="radio" name="ticket_end_date" id="ticket_end_date_n" value="0"> 무</label>
                </td>
              </tr>
              <tr>
                <th>지역</th>
                <td>
                  <select class="form-control" name="area_idx" id="area_idx">
                    <option value="">전체</option>
                    <?php
                      foreach($address_list as $row) {
                    ?>
                        <option value="<?=$row->city_cd_idx?>"><?=$row->city_name?></option>
                    <?php
                      }
                    ?>
                  </select>
                </td>
                <th width="150">탈퇴여부</th>
                <td>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_all" value="" checked> 전체</label>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_n" value="N"> 사용중</label>
                  <label class="radio-inline"><input type="radio" name="del_yn" id="del_y" value="Y"> 탈퇴</label>
                </td>
              </tr>
              <tr>
                <th>평점</th>
                <td>
                  <select class="form-control" name="member_grade" id="member_grade">
                    <option value="">전체</option>
                    <option value="1">★☆☆☆☆</option>
                    <option value="2">★★☆☆☆</option>
                    <option value="3">★★★☆☆</option>
                    <option value="4">★★★★☆</option>
                    <option value="5">★★★★★</option>
                  </select>
                </td>
                <th>승인여부</th>
                <td>
                  <label class="radio-inline"><input type="radio" name="member_accept_yn" id="member_accept_all" value="" checked> 전체</label>
                  <label class="radio-inline"><input type="radio" name="member_accept_yn" id="member_accept_y" value="Y"> 승인</label>
                  <label class="radio-inline"><input type="radio" name="member_accept_yn" id="member_accept_n" value="N"> 미승인</label>
                </td>
              </tr>
              <tr>
                <th>검색어</th>
                <td colspan="3"><input type="text" name="search_text" id="search_text" class="form-control" name="search_text" id="search_text" placeholder="이름, ID, 소속를 입력하세요"> </td>
              </tr>
      			</tbody>
      		</table>
        </form>
    		<div class="text-center mt20">
    			<a href="javascript:dealer_list_get()" class="btn btn-success" id="btn_search" onclick="this.href">검색</a>
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

<script language="javascript">

  $(function(){

    /* Datepicker */
    $("#search_s_date").datepicker({
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
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#search_e_date").datepicker("option", "minDate", selectedDate);

      }
    });

    $("#search_e_date").datepicker({
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
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#search_e_date").datepicker("option", "maxDate", selectedDate);
      }
    });

    /* Datepicker */
    $("#birth_s_date").datepicker({
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
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#birth_e_date").datepicker("option", "minDate", selectedDate);

      }
    });

    $("#birth_e_date").datepicker({
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
      changeMonth: true,
      numberOfMonths: 1,
      onClose: function(selectedDate) {
        $("#birth_s_date").datepicker("option", "maxDate", selectedDate);
      }
    });

  });

  var dealer_list_get = function(page_num=1) {

    var sort_category = $("#sort_category").val();
    var formData = $("#form1").serializeArray();
    formData.push({name: "page_num", value: page_num});
    formData.push({name: "sort_category", value: sort_category});

  	$.ajax({
  		url      : "/relieved_dealer/dealer_choise_list_get",
  		type     : 'POST',
  		dataType : 'html',
  		async    : true,
  		data     : formData,
  		success  : function(dom){
  			$('#result_list').html(dom);

        document.location.hash = "#" + page_num;
  		}
  	});
  }

  dealer_list_get();

  function dealer_choise(member_idx, member_name) {
    window.opener.member_info_setting(member_idx, member_name);
    window.close();
  }

</script>
