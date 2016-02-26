var logText = "";


LOG.info("Extension included");

IEBrowserBot.prototype._windowClosed = function (win) {
  LOG.debug("Using overridden window closed");
  try {
    var c = win.closed;
    // frame windows claim to be non-closed when their parents are closed
    // but you can't access their document objects in that case
    if (!c) {
      try {
        win.document;
      } catch (de) {
        if (de.message == "Permission denied") {
          // the window is probably unloading, which means it's probably not closed yet
          return false;
        }
        else if (/^Access is denied/.test(de.message)) {
          // rare variation on "Permission denied"?
          LOG.debug("IEBrowserBot.windowClosed: got " + de.message + " (this.pageUnloading=" + this.pageUnloading + "); assuming window is unloading, probably not closed yet");
          return false;
        } else {
          // this is probably one of those frame window situations
          LOG.debug("IEBrowserBot.windowClosed: couldn't read win.document, assume closed: " + de.message + " (this.pageUnloading=" + this.pageUnloading + ")");
          return false;
        }
      }
    }
    if (c == null) {
      LOG.debug("IEBrowserBot.windowClosed: win.closed was null, assuming closed");
      return true;
    }
    return c;
  } catch (e) {
    LOG.debug("IEBrowserBot._windowClosed: Got an exception trying to read win.closed; we'll have to take a guess!");

    if (browserVersion.isHTA) {
      if (e.message == "Permission denied") {
        // the window is probably unloading, which means it's not closed yet
        return false;
      } else {
        // there's a good chance that we've lost contact with the window object if it is closed
        return true;
      }
    } else {
      // the window is probably unloading, which means it's not closed yet
      return false;
    }
  }
};



LOG.logHook = function (logLevel, message) {
  logText += logLevel + "(" + (new Date().getTime()) + "): " + message + "\r\n";
}