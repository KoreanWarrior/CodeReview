package hyunjin.com.android_codereview.API.Models;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by park on 2017-08-06.
 */

public class BaseModel implements Parcelable {

  private String code;// 오류체크 코드
  private String code_msg; // 오류체크 메세지

  public BaseModel() {
  }

  public String getCode() {
    return code;
  }

  public void setCode(String code) {
    this.code = code;
  }

  public String getCode_msg() {
    return code_msg;
  }

  public void setCode_msg(String code_msg) {
    this.code_msg = code_msg;
  }

  @Override
  public int describeContents() { return 0; }

  @Override
  public void writeToParcel(Parcel dest, int flags) {
    dest.writeString(this.code);
    dest.writeString(this.code_msg);
  }

  protected BaseModel(Parcel in) {
    this.code = in.readString();
    this.code_msg = in.readString();
  }

}
