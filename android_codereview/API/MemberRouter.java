package hyunjin.com.android_codereview.API;

import java.util.HashMap;

import hyunjin.com.android_codereview.API.Models.MemberModel;
import retrofit2.Call;
import retrofit2.http.FieldMap;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.POST;

/**
 * Created by park on 2017-08-06.
 */

public class MemberRouter extends BaseRouter {

    public static MemberAPI api(String name, String url) {
        return (MemberAPI) retrofit(MemberAPI.class, name, url);
    }

    public interface MemberAPI {
        //로그인
        @FormUrlEncoded
        @POST("memberLogin.app")
        Call<MemberModel> member_login(@FieldMap HashMap<String, Object> map);
    }
}
