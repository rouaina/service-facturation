# config/initializers/railway_ssl.rb
if ENV['RAILWAY_ENVIRONMENT'].present?
  # Railway fournit déjà HTTPS, pas besoin de force_ssl
  Rails.application.config.force_ssl = false
  
  # Optionnel : rediriger HTTP vers HTTPS au niveau Rack
  Rails.application.config.middleware.insert_before 0, Rack::SSL
end