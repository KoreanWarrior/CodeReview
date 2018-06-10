package hyunjin.com.android_codereview.API;


import com.citymanage.BuildConfig;

import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

/**
 * Created by park on 2017-08-06.
 */

public class BaseRouter {
  protected static Object retrofit(Class<?> className) {
    HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
    if (BuildConfig.DEBUG) {
      logging.setLevel(HttpLoggingInterceptor.Level.BODY);
    } else {
      logging.setLevel(HttpLoggingInterceptor.Level.BASIC);
    }

    OkHttpClient.Builder httpClient = new OkHttpClient.Builder();
    httpClient.addInterceptor(logging);

    String host = "";
    if (BuildConfig.DEBUG) {
      host = "http://192.168.0.20:8080/api/";
    } else {
      host = "http://192.168.0.20:8080/api/";
    }

    Retrofit retrofit = new Retrofit.Builder()
        .baseUrl(host)
        .addConverterFactory(GsonConverterFactory.create())
        .client(httpClient.build())
        .build();

    return retrofit.create(className);
  }

  protected static Object retrofit(Class<?> className, String name, String url) {
    HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
    if (BuildConfig.DEBUG) {
      logging.setLevel(HttpLoggingInterceptor.Level.BODY);
    } else {
      logging.setLevel(HttpLoggingInterceptor.Level.BASIC);
    }

    OkHttpClient.Builder httpClient = new OkHttpClient.Builder();
    httpClient.addInterceptor(logging);

    String host = "";
    if (BuildConfig.DEBUG) {
      host = "http://192.168.0.20:8080/api/";
    } else {
      host = "http://192.168.0.20:8080/api/";
    }

    Retrofit retrofit = new Retrofit.Builder()
        .baseUrl(host)
        .addConverterFactory(GsonConverterFactory.create())
        .client(httpClient.build())
        .build();

    return retrofit.create(className);
  }
}


