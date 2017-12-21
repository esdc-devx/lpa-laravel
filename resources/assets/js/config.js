/*
    Defines the API route we are using.
*/
var api_url = '';

switch( process.env.NODE_ENV ){
  case 'development':
    api_url = '/api';
  break;
  case 'production':
    // api_url = 'https://roastandbrew.coffee/api/v1';
  break;
}

export const LPA_CONFIG = {
  API_URL: api_url
};
