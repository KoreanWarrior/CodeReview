package hyunjin.com.android_codereview.Activity;


import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.AppCompatEditText;
import android.util.Log;

import butterknife.BindView;
import butterknife.OnClick;
import hyunjin.com.android_codereview.API.MemberRouter;
import hyunjin.com.android_codereview.API.Models.MemberModel;
import hyunjin.com.android_codereview.MainActivity;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


/**
 * Created by park on 2017-08-06.
 * 로그인화면
 */

public class LoginActivity {
  @BindView(R.id.login_edit_email)
  AppCompatEditText mEmail;
  @BindView(R.id.login_edit_password)
  AppCompatEditText mPass;

  @Override
  protected int inflateLayout() {
    return R.layout.activity_login;
  }

  @Override
  protected String setToolbarText() {
    return null;
  }

  @Override
  protected int setToolbarIcon() {
    return 0;
  }

  @Override
  protected void initLayout() {

  }

  @Override
  protected void initRequest() {

  }

  @OnClick(R.id.login_login)
  public void clickLogin() {
    MemberModel member = MemberModel.getInstance();
    if (member == null) {
      finish();
    }

    apiLogin(mEmail.getText().toString(), mPass.getText().toString());
  }

  public void apiLogin(final String id, final String pass) {

    final Context context = this;

    MemberModel request = MemberModel.getInstance().clear();
    request.setMemberId(id);
    request.setMemberPw(pass);

    MemberRouter.api("로그인", "memberLogin.app").member_login(DataUtils.getMap(request)).enqueue(new Callback<MemberModel>() {
      @Override
      public void onResponse(Call<MemberModel> call, Response<MemberModel> response) {
        if (DataUtils.isSuccessResponce(response)) {
          MemberModel res = response.body();
          res.setMemberId(id);
          res.setMemberPw(pass);

          MemberModel.setInstance(res);

          //메인화면으로 이동
          startActivity(new Intent(context, MainActivity.class));
          //finish();
        } else {
          DataUtils.failDialog(DataUtils.getResponseMsg(response), getSupportFragmentManager(), mActivity);
        }
      }

      @Override
      public void onFailure(Call<MemberModel> call, Throwable t) {
        DataLog.LogE(t.getMessage());
      }
    });
  }
}
