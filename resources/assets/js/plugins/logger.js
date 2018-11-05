// Inspiration: https://github.com/justinkames/vuejs-logger

class Logger {
  constructor(options) {
    const logger = {};
    this.defaults = {
      logLevel          : 'debug',
      separator         : '|',
      stringifyArguments: false,
      showLogLevel      : false,
      showMethodName    : false,
      showConsoleColors : false
    };
    this.logLevels = ['debug', 'info', 'warn', 'error'];

    // merge the default options with the actual provided
    options = Object.assign(this.defaults, options);

    if (!isValidOptions(options, this.logLevels)) {
      throw new Error('Provided options for logger are not valid.');
    }

    // dynamically build the loggers based from the levels
    this.logLevels.forEach(logLevel => {
      // check if the logLevel is found
      if (this.logLevels.indexOf(logLevel) >= this.logLevels.indexOf(options.logLevel)) {
        logger[logLevel] = (...args) => {
          let   methodName         = getMethodName();
          const methodNamePrefix   = options.showMethodName ? `[${methodName}]${options.separator}` : '';
          const logLevelPrefix     = options.showLogLevel ? `[${logLevel}]${options.separator}` : '';
          const formattedArguments = options.stringifyArguments ? args.map(a => JSON.stringify(a)) : args;
          this.log(logLevel, logLevelPrefix, methodNamePrefix, formattedArguments, options.showConsoleColors);
        }
      } else {
        // if not found just assign an empty function
        logger[logLevel] = () => {};
      }
    });
    return logger;
  }

  log(logLevel = false, logLevelPrefix = false, methodNamePrefix = false, formattedArguments = false, showConsoleColors = false) {
    if (showConsoleColors && (logLevel === 'warn' || logLevel === 'error')) {
      console[logLevel](logLevelPrefix, methodNamePrefix, ...formattedArguments);
    } else {
      // debug
      console.log(logLevelPrefix, methodNamePrefix, ...formattedArguments);
    }
  }
}

function isValidOptions(options, logLevels) {
  if (!(options.logLevel && typeof options.logLevel === 'string' && logLevels.indexOf(options.logLevel) > -1)) {
    return false;
  }
  if (options.stringifyArguments && typeof options.stringifyArguments !== 'boolean') {
    return false;
  }
  if (options.showLogLevel && typeof options.showLogLevel !== 'boolean') {
    return false;
  }
  if (options.showConsoleColors && typeof options.showConsoleColors !== 'boolean') {
    return false;
  }
  if (options.separator && (typeof options.separator !== 'string' || (typeof options.separator === 'string' && options.separator.length > 3))) {
    return false;
  }
  return !(options.showMethodName && typeof options.showMethodName !== 'boolean');
}
function getMethodName() {
  let error = {};
  try { throw new Error('') } catch (e) { error = e };
  let stackTrace = error.stack.split('\n')[3];
  if (/ /.test(stackTrace)) {
    stackTrace = stackTrace.trim().split(' ')[1];
  }
  if (stackTrace && stackTrace.includes('.')) {
    stackTrace = stackTrace.split('.')[1];
  }
  return stackTrace;
}

const LoggerPlugin = {
  install(Vue, options) {
    Vue.$log = new Logger(options);
    Vue.prototype.$log = Vue.$log;
  }
};

export default LoggerPlugin;
