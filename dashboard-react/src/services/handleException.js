export const HandleLogin = (error) => {
//@TODO Remover esse console.log

    console.log(error)
    console.log(error.data)

    if (typeof error.data !== 'undefined') {

        var msgError = error.data.content ? error.data.content : error.content

        return `${msgError}`

    } else {

        return 'Estamos com problema para autenticar você tente novamente mais tarde'

    }
}
