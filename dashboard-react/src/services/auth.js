import {removeAttempts} from "./api";

export const TOKEN_KEY = 'DASHBOARD_TOKEN_KEY';
export const PERMISSIONS_KEY = 'DASHBOARD_PERMISSIONS_KEY';

//@TODO Verificar revalidação do token
export const isAuthenticated = () => {
    return localStorage.getItem(TOKEN_KEY) !== null
    // console.log(localStorage.getItem(TOKEN_KEY));
    // var objectToken = JSON.parse(localStorage.getItem(TOKEN_KEY))
    // console.log(objectToken.expires);
    // console.log(new Date().getTime());
    // console.log(new Date().getTime()/1000);
    // console.log(objectToken.expires < new Date().getTime()/1000);
    // if (objectToken.expires < new Date().getTime()/1000) {
    //
    // }
};

export const getToken = () => {
    var objectToken = JSON.parse(localStorage.getItem(TOKEN_KEY))

    if (objectToken === null) {
        return ''
    }

    return objectToken.token;
};

export const login = (token, expires_in) => {
    localStorage.setItem(TOKEN_KEY, JSON.stringify({
        token: token,
        expires: expires_in
    }))
};

export const logout = () => {
    removeTokenKey()
    removePermission()
    removeAttempts()
};

export const removeTokenKey = () => {
    localStorage.removeItem(TOKEN_KEY)
}

export const removePermission = () => {
    localStorage.removeItem(PERMISSIONS_KEY)
}