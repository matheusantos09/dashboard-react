import api from './api'
import CryptoJS from "crypto-js";
import {ApiRouteList} from './routes';
import {PERMISSIONS_KEY} from "./auth";

// const SECRET_KEY = '19)a(*Aasd'
const SECRET_CRYPTOJS_KEY = '123'

export const savePermissions = async () => {

    api.get(ApiRouteList.permissions)
        .then((response) => {

            if (response.data.error) {
                throw response;
            }

            setPermissions(response.data.content)

        })
        .catch((error) => {
            console.log('CATCH');
            console.log(error);
        });

}

export const setPermissions = (permissions) => {

    // ENCRYPT
    var encryptedArray = []
    permissions.map((item) => {
        encryptedArray.push(CryptoJS.AES.encrypt(item, SECRET_CRYPTOJS_KEY).toString())
    })

    localStorage.setItem(PERMISSIONS_KEY, JSON.stringify({
        permissions: encryptedArray
    }))
}

export const getPermissions = () => {
    var object = JSON.parse(localStorage.getItem(PERMISSIONS_KEY))
    var decryptedArray = []

    if (object.permissions === null) {
        return ''
    }

// DECRYPT
    object.permissions.map((item) => {
        var bytes = CryptoJS.AES.decrypt(String(item), SECRET_CRYPTOJS_KEY);
        var decryptedData = bytes.toString(CryptoJS.enc.Utf8);
        decryptedArray.push(decryptedData)
    })

    return decryptedArray;
}

export const verifyPermission = (permission) => {
    var permissions = getPermissions();

    if (permission === null) {
        return false
    }

    return permissions.indexOf(permission) !== -1

}

