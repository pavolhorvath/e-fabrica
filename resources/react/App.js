import React from "react"
import {CookiesProvider} from "react-cookie";
import Modal from "react-modal"
import EmployeesList from "./EmployeesList";

Modal.setAppElement('#root');

export default class App extends React.Component
{
	render() {
		return (
			<CookiesProvider>
				<div className="app-wrapper">
					<EmployeesList />
				</div>
			</CookiesProvider>
		)
	}
}