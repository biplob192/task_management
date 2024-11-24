import axios from "axios";
import Api from "../services/api/Api";

async function validateToken(token) {
  try {
    // Make a backend call to verify the token using axios
    const response = await axios.get("http://127.0.0.1:8000/api/validate-token", {
      headers: {
        Accept: "application/json",
        Authorization: `Bearer ${token}`,
      },
    });

    // If the response is not ok, the token is likely invalid or expired
    return response.status === 200 ? true : refreshToken();
  } catch (error) {
    console.log("error");
    // If there's any error (e.g., network issues), treat the token as invalid
    return refreshToken();
  }
}

async function refreshToken() {
  try {
    const response = await axios.post("http://127.0.0.1:8000/api/refresh", null, {
      headers: {
        Authorization: "Bearer " + localStorage.getItem("refresh_token"),
      },
    });

    const newAccessToken = response.data.data.access_token;
    const newRefreshToken = response.data.data.refresh_token;

    if (newAccessToken) {
      console.log("inside newAccessToken");
      localStorage.setItem("access_token", newAccessToken);
      localStorage.setItem("refresh_token", newRefreshToken);

      // Update the Token in Api
      Api.defaults.headers["Authorization"] = "Bearer " + newAccessToken;

      return true;
    }

    return false;
  } catch (error) {
    return false;
  }
}

export { validateToken };
