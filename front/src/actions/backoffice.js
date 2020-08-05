export const GET_COMMANDS = 'GET_COMMANDS';
export const COMMANDS_TO_STATE = 'COMMANDS_TO_STATE';
export const GET_CLIENTS = 'GET_CLIENTS';

export const getClients = (clients) => ({
  type: GET_CLIENTS,
  clients,
});

export const commandsToState = (commands) => ({
  type: COMMANDS_TO_STATE,
  commands,
});

export const getCommands = () => ({
  type: GET_COMMANDS,
});
