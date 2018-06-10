package hyunjin.com.android_codereview.API.Models;

import android.os.Parcel;
import android.os.Parcelable;

/**
 * Created by park on 2017-08-06.
 */

public class MemberModel extends BaseModel implements Parcelable{

    public static final Creator<MemberModel> CREATOR = new Creator<MemberModel>() {
        @Override
        public MemberModel createFromParcel(Parcel source) {return new MemberModel(source);}

        @Override
        public MemberModel[] newArray(int size) {return new MemberModel[size];}
    };
    public static MemberModel mInstance;

    private String memberName;
    private String memberId;
    private String memberPw;
    private String cityCode;
    private String cityName;
    private String stateCode;
    private String stateName;

    public String getMemberName() {
        return memberName;
    }

    public String getMemberId() {
        return memberId;
    }

    public String getMemberPw() {
        return memberPw;
    }

    public String getCityCode() {
        return cityCode;
    }

    public String getCityName() {
        return cityName;
    }

    public String getStateCode() {
        return stateCode;
    }

    public String getStateName() {
        return stateName;
    }

    public void setMemberName(String memberName) {
        this.memberName = memberName;
    }

    public void setMemberId(String memberId) {
        this.memberId = memberId;
    }

    public void setMemberPw(String memberPw) {
        this.memberPw = memberPw;
    }

    public void setCityCode(String cityCode) {
        this.cityCode = cityCode;
    }

    public void setCityName(String cityName) {
        this.cityName = cityName;
    }

    public void setStateCode(String stateCode) {
        this.stateCode = stateCode;
    }

    public void setStateName(String stateName) {
        this.stateName = stateName;
    }

    public MemberModel() {}

    protected MemberModel(Parcel in) {
        super(in);
        this.memberName = in.readString();
        this.memberId = in.readString();
        this.memberPw = in.readString();
        this.cityCode = in.readString();
        this.cityName = in.readString();
        this.stateCode = in.readString();
        this.stateName = in.readString();
    }

    public static MemberModel getInstance() {
        if (mInstance == null) {
            mInstance = new MemberModel();
        }
        return mInstance;
    }

    public static void setInstance(MemberModel mInstance) {
        MemberModel.mInstance = mInstance;
    }

    public static MemberModel getmInstance() {
        return mInstance;
    }

    public static void setmInstance(MemberModel mInstance) {
        MemberModel.mInstance = mInstance;
    }

    @Override
    public int describeContents() { return 0; }

    @Override
    public void writeToParcel(Parcel dest, int flags) {
        super.writeToParcel(dest, flags);
        dest.writeString(this.memberName);
        dest.writeString(this.memberId);
        dest.writeString(this.memberPw);
        dest.writeString(this.cityCode);
        dest.writeString(this.cityName);
        dest.writeString(this.stateCode);
        dest.writeString(this.stateName);
    }
}
