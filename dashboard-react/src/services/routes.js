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
    },
}

//@TODO fazer esquema de validação para as permissões

export const ApiRouteList = {
    login: AuthRoutesPrefix + '/login',
    signup: AuthRoutesPrefix + '/signup',
    tasks: AuthRoutesPrefix + '/task',
    tasksList: AuthRoutesPrefix + '/task-list',
    completedTasks: AuthRoutesPrefix + '/task/completed',
    saveTask: AuthRoutesPrefix + '/task/save',
    taskDestroy: AuthRoutesPrefix + '/task/destroy',
    changeStatus: AuthRoutesPrefix + '/change-task',
    taskUpdate: AuthRoutesPrefix + '/task/edit/',
    eventTimer: AuthRoutesPrefix + '/timer-event',
    saveConfigUser: AuthRoutesPrefix + '/user/save',
    loadConfigUser: AuthRoutesPrefix + '/user/load',
    startSnooze: AuthRoutesPrefix + '/user/snooze/start',
    endSnooze: AuthRoutesPrefix + '/user/snooze/end',
    randomPhase: AuthRoutesPrefix + '/phase/random',
    userUploadImage: AuthRoutesPrefix + '/user/upload',
    notificationIndex: AuthRoutesPrefix + '/notification'
}


/*
* Itens do menu:
*   path (string) => Rota para o recurso
*   name (string) => Nome para o item no menu
*   icon (string) => Ícone do item (FontAwesome 5)
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