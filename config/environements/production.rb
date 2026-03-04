# config/environments/production.rb
Rails.application.configure do
  # Production settings
  config.cache_classes = true
  config.eager_load = true
  
  # DÉSACTIVER force_ssl pour Railway
  config.force_ssl = false
  
  # Le reste de votre config...
end