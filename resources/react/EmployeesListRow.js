import React from "react"
import EmployeeDetail from "./EmployeeDetail";
import EmployeeEdit from "./EmployeeEdit";
import EmployeeRemove from "./EmployeeRemove";
import axios from "axios";
import {toast} from "react-toastify";

export default class EmployeesListRow extends React.Component
{
	state = {
		showDetail: false,
		showEdit: false,
		showRemove: false
	}

	changeShowDetail = () => {
		this.setState({
			showDetail: !this.state.showDetail
		})
	}

	changeShowEdit = () => {
		this.setState({
			showEdit: !this.state.showEdit
		})
	}

	changeShowRemove = () => {
		this.setState({
			showRemove: !this.state.showRemove
		})
	}

	saveEmployee = (employeeData) => {
		axios.post('employeeupdate', employeeData)
			.then(response => {
				if (response.data.success) {
					this.changeShowEdit()
					this.props.updateListItem(this.props.index, response.data.employee)
					toast.success(response.data.success)
			 	} else {
					response.data.errors.forEach(error => toast.error(error))
				}
			})
	}

	removeEmployee = () => {
		axios.post('employeeremove', {id: this.props.employee.id})
			.then(response => {
				if (response.data.success) {
					this.changeShowRemove()
					this.props.removeFromList(this.props.index)
					toast.success(response.data.success)
				} else {
					response.data.errors.forEach(error => toast.error(error))
				}
			})
	}

	render() {
		return (
			<tr>
				<td>{this.props.employee.firstName} {this.props.employee.lastName}</td>
				<td>{this.props.employee.phone}</td>
				<td>{this.props.employee.email}</td>
				<td>
					<i className="show-employee action-icon-button fas fa-user-tag" title="Detail zamestnanca" onClick={this.changeShowDetail} />
					<EmployeeDetail open={this.state.showDetail} close={this.changeShowDetail} employee={this.props.employee} />
					<i className="edit-employee action-icon-button fas fa-user-edit" title="Upraviť údaje o zamestnancovi" onClick={this.changeShowEdit} />
					<EmployeeEdit open={this.state.showEdit} close={this.changeShowEdit} employee={this.props.employee} save={this.saveEmployee} title="Úprava zamestnanca" />
					<i className="remove-employee action-icon-button fas fa-user-times" title="Odstrániť zamestnanca" onClick={this.changeShowRemove} />
					<EmployeeRemove open={this.state.showRemove} close={this.changeShowRemove} remove={this.removeEmployee} />
				</td>
			</tr>
		)
	}
}