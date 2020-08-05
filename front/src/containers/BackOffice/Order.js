import { connect } from 'react-redux';
import Order from '../../components/BackOffice/Order';
import { getCommand } from '../../actions/backoffice';

const mapStateToProps = (state) => ({
  command: state.backoffice.command,
});

const mapDispatchToProps = (dispatch) => ({
  getCommand: (id) => {
    dispatch(getCommand(id));
  },
});

export default connect(mapStateToProps, mapDispatchToProps)(Order);
