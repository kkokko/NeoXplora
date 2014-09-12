object NeoXploraService: TNeoXploraService
  OldCreateOrder = False
  AllowPause = False
  DisplayName = 'NeoXplora Service'
  ErrorSeverity = esIgnore
  OnShutdown = ServiceShutdown
  OnStart = ServiceStart
  OnStop = ServiceStop
  Height = 150
  Width = 215
end
