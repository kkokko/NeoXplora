object NasService: TNasService
  OldCreateOrder = False
  AllowPause = False
  DisplayName = 'Nas Server Service'
  ErrorSeverity = esIgnore
  OnShutdown = ServiceShutdown
  OnStart = ServiceStart
  OnStop = ServiceStop
  Height = 150
  Width = 215
end
