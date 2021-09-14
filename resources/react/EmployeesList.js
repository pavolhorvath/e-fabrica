import React from "react"
import axios from "axios"
import EmployeesListRow from "./EmployeesListRow";
import EmployeeEdit from "./EmployeeEdit";

import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css"
import "../less/reactToastifyVariables.less"
import EmployeesAge from "./EmployeesAge";

export default class EmployeesList extends React.Component
{
	state = {
		employeesList: [],
		showAdd: false,
		showAge: false
	}

	constructor() {
		super();
		this.loadEmployeesList()
	}

	loadEmployeesList = () => {
		axios.get('employeeslist')
			.then(response => {
				this.setState({
					employeesList: response.data
				})
			})
	}

	changeShowAdd = () => {
		this.setState({
			showAdd: !this.state.showAdd
		})
	}

	changeShowAge = () => {
		this.setState({
			showAge: !this.state.showAge
		})
	}

	addEmployee = (employeeData) => {
		axios.post('employeeadd', employeeData)
			.then(response => {
				if (response.data.success) {
					this.changeShowAdd()
					let employeesList = this.state.employeesList
					employeesList.push(response.data.employee)
					this.setState({
						employeesList: employeesList
					})
					toast.success(response.data.success)
				} else {
					response.data.errors.forEach(error => toast.error(error))
				}
			})
	}

	updateListItem = (index, employee) => {
		let employeesList = this.state.employeesList
		employeesList[index] = employee
		this.setState({
			employeesList: employeesList
		})
	}

	removeFromList = (index) => {
		let employeesList = this.state.employeesList
		delete employeesList[index]
		this.setState({
			employeesList: employeesList
		})
	}

	render()
	{
		let newEmployee = {
			id: 0,
			firstName: "",
			lastName: "",
			phone: "",
			email: "",
			birthDate: "",
			age: "",
			employmentDate: "",
			employmentAge: ""
		}

		return (
			<div className="employees-list-wrapper">
				<h1 className="employees-list-title">Zoznam zamestnancov</h1>

				<table className="employees-list-table">
					<tbody>
						<tr>
							<th>Meno a priezvisko</th>
							<th>Telefón</th>
							<th>E-mail</th>
						</tr>
						{this.state.employeesList.map((employee, index) => {
							return (
								<EmployeesListRow key={employee.id} employee={employee} index={index} updateListItem={this.updateListItem} removeFromList={this.removeFromList} />
							)
						})}
					</tbody>
				</table>

				<div className="employees-button-wrapper">
					<div className="add-employee button" onClick={this.changeShowAdd}>
						<i className="icon fas fa-user-plus" />
						<span className="text">Pridať zamestnanca</span>
					</div>
					<EmployeeEdit open={this.state.showAdd} close={this.changeShowAdd} employee={newEmployee} save={this.addEmployee} title="Pridanie zamestnanca" />
					<div className="show-graph button" onClick={this.changeShowAge}>
						<i className="icon fas fa-chart-bar" />
						<span className="text">Grafické zobrazenie veku</span>
					</div>
					<EmployeesAge open={this.state.showAge} close={this.changeShowAge} employees={this.state.employeesList} />
				</div>

				<ToastContainer hideProgressBar theme="colored"/>
			</div>
		)
	}
}