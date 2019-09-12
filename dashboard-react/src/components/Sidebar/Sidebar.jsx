import React, {Component} from "react";

/* Import routes */
import {MenuSidebar} from "../../services/routes";

import logo from '../../assets/img/AdminLTELogo.png'
import {Link} from "react-router-dom";
import {verifyPermission} from "../../services/permissions";

class Sidebar extends Component {

    constructor() {
        super();

        this.menuListItems = []

    }

    //@TODO alterar o WillMount depois, pois esse metodo foi descontinuado
    componentWillMount() {

        let menuList = [];

        // eslint-disable-next-line
        MenuSidebar.map((item, index) => {

            if (!item.permission || verifyPermission(item.permission)) {
                let icon = 'nav-icon ';
                icon += item.icon ? item.icon : '';

                if (item.submenu) {

                    let subMenu = [];

                    // eslint-disable-next-line
                    item.submenu.map((itemS, indexS) => {

                        let subIcon = 'nav-icon ';
                        subIcon += itemS.icon ? itemS.icon : '';

                        subMenu.push(
                            <li className="nav-item" key={indexS + 10}>
                                <Link to={item.path} className="nav-link">
                                    <i className={subIcon}/>
                                    <p>
                                        {item.name}
                                        {item.badge ?
                                            <span
                                                className={"right badge " + item.badge.color}>{item.badge.title}</span>
                                            :
                                            ''
                                        }
                                    </p>
                                </Link>
                            </li>
                        )
                    })

                    menuList.push(
                        <li className="nav-item has-treeview" key={index}>
                            <a href={(e) => {
                                e.preventDefault()
                            }
                            } className="nav-link">
                                <i className={icon}/>
                                <p>
                                    {item.name}
                                    <i className="fas fa-angle-left right"/>
                                    {item.badge ?
                                        <span className={"right badge " + item.badge.color}>{item.badge.title}</span>
                                        :
                                        ''
                                    }
                                </p>
                            </a>
                            <ul className="nav nav-treeview">
                                {subMenu}
                            </ul>
                        </li>
                    )

                } else {
                    menuList.push(
                        <li className="nav-item" key={index}>
                            <Link to={item.path} className="nav-link">
                                <i className={icon}/>
                                <p>
                                    {item.name}
                                    {item.badge ?
                                        <span className={"right badge " + item.badge.color}>{item.badge.title}</span>
                                        :
                                        ''
                                    }
                                </p>
                            </Link>
                        </li>
                    )
                }
            }
        });

        this.menuListItems = menuList

    }

    render() {

        return (
            <aside className="main-sidebar sidebar-dark-primary elevation-4">
                <a href="index3.html" className="brand-link">
                    <img src={logo} alt="AdminLTE Logo"
                         className="brand-image img-circle elevation-3"/>
                    <span className="brand-text font-weight-light">Dashboard Netzee</span>
                </a>

                <div className="sidebar">

                    {/*<div className="user-panel mt-3 pb-3 mb-3 d-flex">*/}
                    {/*    <div className="image">*/}
                    {/*        <img src="dist/img/user2-160x160.jpg" className="img-circle elevation-2" alt="User Image"/>*/}
                    {/*    </div>*/}
                    {/*    <div className="info">*/}
                    {/*        <a href="#" className="d-block">Alexander Pierce</a>*/}
                    {/*    </div>*/}
                    {/*</div>*/}

                    <nav className="mt-2">
                        <ul className="nav nav-pills nav-sidebar flex-column"
                            data-widget="treeview"
                            role="menu"
                            data-accordion="false"
                        >
                            {
                                this.menuListItems
                            }

                        </ul>
                    </nav>
                </div>
            </aside>
        )

    }

}

export default Sidebar