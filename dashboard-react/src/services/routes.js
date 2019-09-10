import React from 'react'
import {BrowserRouter, Route, Switch, Redirect} from 'react-router-dom'
import {isAuthenticated} from "./auth"

//Import of pages
import Login from '../views/Login/Login'
import {NotFoundLogged, NotFoundNotLogged} from '../views/NotFound/NotFound'
import Logout from '../views/Logout/Logout'

const AppNameRoute = '/app'
const AuthRoutesPrefix = '/auth'

export const RouteList = {
    login: {
        path: '/',
        name: 'Login'
    },
    logout: {
        path: '/logout',
        name: 'Logout',
        icon: 'fas fa-sign-out-alt',
        permission: ''
    },
}

//@TODO fazer esquema de validação para as permissões

export const ApiRouteList = {
    login: AuthRoutesPrefix + '/login',
    permissions: AuthRoutesPrefix + '/permissions',
}


/*
* Itens do menu:
*   path (string) => Rota para o recurso
*   name (string) => Nome para o item no menu
*   icon (string) => Ícone do item (FontAwesome 5)
*   permission (string) => Nome da permissão para a exibição deste recurso
*       * Obs: para liberação do recurso a todos NÃO defina essa propriedade
*
*   Opcional
*       badge (Objeto)
*           title => título
*           color => Cor da badge
*/
export const MenuSidebar = [
    {
        path: RouteList.logout.path,
        name: RouteList.logout.name,
        icon: RouteList.logout.icon,
    },
    {
        path: 'Lista de usuários',
        name: 'Lista de usuários',
        icon: RouteList.logout.icon,
    }
]

const PrivateRoute = ({component: Component, ...rest}) => (
    <Route
        {...rest}
        render={props => isAuthenticated() ?
            (
                <Component {...props}/>
            ) : (
                <Redirect to={{pathname: '/', state: {from: props.location}}}/>
            )
        }
    />
);

//@TODO melhor exibição da página de 404 quando não está logado
const PageNotFound = () => (
    isAuthenticated()         ?
        <NotFoundLogged />    :
        <NotFoundNotLogged />
)

const Routes = () => (
    <BrowserRouter>
        <Switch>
            <Route exact path={RouteList.login.path} component={Login}/>
            <PrivateRoute exact path={RouteList.logout.path} component={Logout}/>
            <Route path='*' component={PageNotFound}/>
        </Switch>
    </BrowserRouter>
);

export default Routes;