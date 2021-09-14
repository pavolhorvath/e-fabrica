import React from "react"
import Modal from "react-modal"

export default class EmployeeDetail extends React.Component
{
	render() {
		let year
		if (this.props.employee.employmentAge == 0) {
			year = 'rokov'
		} else if (this.props.employee.employmentAge == 1) {
			year = 'rok'
		}  else if (this.props.employee.employmentAge < 5) {
			year = 'roky'
		} else {
			year = 'rokov'
		}

		let splitBD = this.props.employee.birthDate.split('.')
		if (splitBD[0].charAt(0) == "0") splitBD[0] = splitBD[0].substr(1)
		if (splitBD[1].charAt(0) == "0") splitBD[1] = splitBD[1].substr(1)
		let birthDate = splitBD[0] + '.' + splitBD[1] + '.' + splitBD[2]

		let splitED = this.props.employee.employmentDate.split('.')
		if (splitED[0].charAt(0) == "0") splitED[0] = splitED[0].substr(1)
		if (splitED[1].charAt(0) == "0") splitED[1] = splitED[1].substr(1)
		let employmentDate = splitED[0] + '.' + splitED[1] + '.' + splitED[2]

		return (
			<Modal isOpen={this.props.open} className="modal-wrapper" overlayClassName="modal-overlay">
				<div className="employee-detail-wrapper">
					<div className="modal-title">Detail zamestnanca</div>

					<table className="employee-detail-table">
						<tbody>
							<tr>
								<td>
									<span className="label">Krstné meno:</span>
									<span className="value">{this.props.employee.firstName}</span>
								</td>
								<td>
									<span className="label">Priezvisko:</span>
									<span className="value">{this.props.employee.lastName}</span>
								</td>
							</tr>
						<tr>
							<td>
								<span className="label">Telefón:</span>
								<span className="value">{this.props.employee.phone}</span>
							</td>
						</tr>
						<tr>
							<td>
								<span className="label">E-mail:</span>
								<span className="value">{this.props.employee.email}</span>
							</td>
						</tr>
						<tr>
							<td>
								<span className="label">Dátum narodenia:</span>
								<span className="value">{birthDate}</span>
							</td>
							<td>
								<span className="label">Vek:</span>
								<span className="value">{this.props.employee.age}</span>
							</td>
						</tr>
						<tr>
							<td>
								<span className="label">Dátum nástupu:</span>
								<span className="value">{employmentDate}</span>
							</td>
							<td>
								<span className="label">Doba zamestnania:</span>
								<span className="value">{this.props.employee.employmentAge} {year}</span>
							</td>
						</tr>
						</tbody>
					</table>

					<div className="modal-button-wrapper">
						<div className="close-modal button" onClick={this.props.close}>
							<i className="icon fas fa-times" />
							<span className="text">Zavrieť</span>
						</div>
					</div>
				</div>
			</Modal>
		)
	}
}