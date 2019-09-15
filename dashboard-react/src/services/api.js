import axios from 'axios'
import {getToken, login, removePermission, removeTokenKey} from "./auth"
import {ApiRouteList} from "./routes";
import Swal from "sweetalert2";

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
    @TODO Melhorar as validações de erros que o sistema exibe
 * Validando caso o token do usuário tenha expirado
 */

api.interceptors.response.use(response => (
    response
), error => (
    error.response
));

// api.interceptors.response.use(
//     (response) => response,
//     (error) => {
//
//         var attemps = getSaveAttempts()
//
//         if (DASHBOARD_MAX_ATTEMPTS >= attemps) {
//
//             saveAttempts()
//
//             if (error.response.status === 401) {
//
//                 removeTokenKey()
//
//                 api.get(ApiRouteList.refresh)
//                     .then((response) => {
//
//                         if (response.data.error) {
//                             throw response;
//                         }
//
//                         login(response.data.token, response.data.expires_in)
//
//                     }).catch((error) => [
//                     console.log(error)
//                 ])
//             }
//
//             return Promise.reject(error);
//         }
//
//         if (error.response.status === 402) {
//
//             Swal.fire({
//                 title: '',
//                 text: 'You will not be able to recover this imaginary file!',
//                 type: 'warning',
//                 showCancelButton: true,
//                 confirmButtonText: 'Yes, delete it!',
//                 cancelButtonText: 'No, keep it'
//             }).then((result) => {
//                 if (result.value) {
//                     Swal.fire(
//                         'Deleted!',
//                         'Your imaginary file has been deleted.',
//                         'success'
//                     )
//                     // For more information about handling dismissals please visit
//                     // https://sweetalert2.github.io/#handling-dismissals
//                 } else if (result.dismiss === Swal.DismissReason.cancel) {
//                     Swal.fire(
//                         'Cancelled',
//                         'Your imaginary file is safe :)',
//                         'error'
//                     )
//                 }
//             })
//         }
//
//         if (error.response.status === 500) {
//
//             Swal.fire({
//                 title: '500',
//                 text: 'Tivemos um problema, tente acessar o sistema novamente',
//                 type: 'warning',
//                 showCancelButton: false,
//                 confirmButtonText: 'Login',
//             }).then((result) => {
//                 removeAttempts()
//                 removeTokenKey()
//                 removePermission()
//                 window.location.href = '/'
//             })
//         }
//
//         //@TODO alterar para tirar esse refresh
//         // removeAttempts()
//         // removeTokenKey()
//         // removePermission()
//         // window.location.href = '/'
//
//     }
// )

export const saveAttempts = () => {

    var attempts = getSaveAttempts()

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