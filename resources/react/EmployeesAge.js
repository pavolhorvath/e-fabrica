import React from "react"
import Modal from "react-modal";

export default class EmployeesAge extends React.Component
{
	render() {
		let minAge
		this.props.employees.forEach((employee, index) => {
			if (!index || employee.age < minAge) {
				minAge = employee.age
			}
		})
		minAge -= 5
		if (minAge < 0) {
			minAge = 0
		}

		return (
			<Modal isOpen={this.props.open} className="modal-wrapper" overlayClassName="modal-overlay">
				<div className="modal-title">Grafické zobrazenie veku</div>

				<table className="employees-age-table">
					<tbody>
						{this.props.employees.map(employee => {
							return (
								<tr key={employee.id}>
									<td className="employee-name">{employee.firstName} {employee.lastName}</td>
									<td className="employee-age">
										<span style={{width: (employee.age-minAge)*10+'px'}}>{employee.age}</span>
									</td>
								</tr>
							)
						})}
					</tbody>
				</table>

				<div className="modal-button-wrapper">
					<div className="close-modal button" onClick={this.props.close}>
						<i className="icon fas fa-times" />
						<span className="text">Zavrieť</span>
					</div>
				</div>
			</Modal>
		)
	}
}