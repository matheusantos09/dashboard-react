import axios from 'axios'
import {getToken, login} from "./auth"
import {ApiRouteList} from "./routes";

export const DASHBOARD_ATTEMPTS = 'DASHBOARD_ATTEMPTS'
export const DASHBOARD_MAX_ATTEMPTS = 5

const api = axios.create({
    baseURL: 'http://127.0.0.1:9999/api'
    // baseURL: 'http://dashboard-api.test/api'
});

api.interceptors.request.use(async config => {
    const token = getToken();
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config
});

/*
 * Validando caso o token do usuário tenha expirado
 */
api.interceptors.response.use(
    (response) => response,
    (error) => {

        console.log(error.response)

        var attemps = getSaveAttempts()

        if (DASHBOARD_MAX_ATTEMPTS >= attemps) {

            saveAttempts()

            if (error.response.status === 401) {
                api.get(ApiRouteList.refresh)
                    .then((response) => {

                        if (response.data.error) {
                            throw response;
                        }

                        login(response.data.token, response.data.expires_in)

                    }).catch((error) => [
                    console.log(error)
                ])
            }

            return Promise.reject(error);
        }

        //@TODO alterar para tirar esse refresh
        removeAttempts()
        window.location.href = '/'

    }
)

export const saveAttempts = () => {

    var attempts = getSaveAttempts()
    console.log('TENTATIVAAS: ' + attempts)

    localStorage.setItem(DASHBOARD_ATTEMPTS, JSON.stringify({
        attempts: attempts + 1
    }))

}

export const getSaveAttempts = () => {
    var object = JSON.parse(localStorage.getItem(DASHBOARD_ATTEMPTS))

    if (object === null) {
        return 0
    }

    return object.attempts;
};

export const removeAttempts = () => {
    localStorage.removeItem(DASHBOARD_ATTEMPTS)
};

export default api;