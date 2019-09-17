import React from 'react'
import {ToastContainer, toast} from 'react-toastify'

export const ToastContainerCustom = () => (
    <ToastContainer
        position="bottom-right"
        autoClose={5000}
        hideProgressBar={false}
        newestOnTop={false}
        closeOnClick
        rtl={false}
        pauseOnVisibilityChange
        draggable
        pauseOnHover/>
)

export function notify(data) {

    let msg = data.msg ? data.msg : 'Message default. Define one specifc message';
    let time = data.time ? data.time : 3000;

    switch (data.status) {
        case 'default':
            toast(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break

        case 'error':
            toast.error(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break

        case 'success':
            toast.success(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break

        case 'warn':
            toast.warn(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break

        case 'info':
            toast.info(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break

        default:
            toast(msg, {
                position: "bottom-right",
                autoClose: time,
                hideProgressBar: false,
                closeOnClick: true,
                pauseOnHover: true,
                draggable: true,
            });
            break
    }
}