require_relative 'loader'

module TWEyes

  class Application

    public

    def initialize
      @api          = {}
      @configure    = {}
      @controller   = {}
      @database     = {}
      @normatter    = {}
      @logger       = {}
      @buildr       = {}
      @orchestrator = {}

      initialize_configure
      initialize_controller
      initialize_database
      initialize_driver
      initialize_api
      initialize_formatter
      initialize_logger
      initialize_builder
      initialize_orchestrator
    end

    protected

    private

    def initialize_configure

      @configure = {
        yahoo: {
          self: TWEyes::Configure::Yahoo.new,
          auctions: {
            self: TWEyes::Configure::Yahoo::Auctions.new,
          },
        },
        ebay: {
          self: TWEyes::Configure::Ebay.new,
          com: {
            self: TWEyes::Configure::Ebay::Com.new,
          },
        },
        mvc: {
          self: TWEyes::Configure::MVC.new,
          potal: {
            self: TWEyes::Configure::MVC::Potal.new,
          },
          system: {
            self: TWEyes::Configure::MVC::System.new,
            research: {
              self: TWEyes::Configure::MVC::System::Research.new,
              ebay: {
                self: TWEyes::Configure::MVC::System::Research::Ebay.new,
              },
              yahoo: {
                self: TWEyes::Configure::MVC::System::Research::Yahoo.new,
                auctions: {
                  self: TWEyes::Configure::MVC::System::Research::Yahoo::Auctions.new,
                },
              },
            },
          },
        },
        api: {
          yahoo: {
            auctions: TWEyes::Configure::API::Yahoo::Auctions.new,
            shopping: {
              self: TWEyes::Configure::API::Yahoo::Shopping.new,
              circus: TWEyes::Configure::API::Yahoo::Shopping::Circus.new,
            }
          },
          ebay: {
            self: TWEyes::Configure::API::Ebay.new,
            trading: {
              self: TWEyes::Configure::API::Ebay::Trading.new,
              production: TWEyes::Configure::API::Ebay::Trading::Production.new,
            },
            finding: {
              self: TWEyes::Configure::API::Ebay::Finding.new,
              production: {
                self: TWEyes::Configure::API::Ebay::Finding::Production.new,
              },
            },
            business_policies_management: {
              self: TWEyes::Configure::API::Ebay::BusinessPoliciesManagement.new,
              production: {
                self: TWEyes::Configure::API::Ebay::BusinessPoliciesManagement::Production.new,
              },
            },
          },
          chatwork: TWEyes::Configure::API::ChatWork.new,
          amazon: {
            mws: {
              core: TWEyes::Configure::API::Amazon::MWS.new,
              developer: {
                self: TWEyes::Configure::API::Amazon::MWS::Developer.new,
                jp: {
                  self: TWEyes::Configure::API::Amazon::MWS::Developer::Jp.new,
                },
              },
              submit_feed: TWEyes::Configure::API::Amazon::MWS::SubmitFeed.new,
              reports: TWEyes::Configure::API::Amazon::MWS::Reports.new,
            }
          }
        },
        database: {
          mysql: {
            self: TWEyes::Configure::Database::MySQL.new,
            m: TWEyes::Configure::Database::MySQL::M.new,
          },
        },
        driver: {
          web: {
            mechanize: TWEyes::Configure::Driver::Web::Mechanize.new,
            selenium: TWEyes::Configure::Driver::Web::Selenium.new,
            proxies: {
              mpp: TWEyes::Configure::Driver::Web::Proxies::MPP.new,
            }
          }
        },
        controller: {
          chatwork: {
            self: TWEyes::Configure::Controller::ChatWork.new,
          },
          monitor: {
            process: {
              self: TWEyes::Configure::Controller::Monitor::Process.new,
            },
          },
          stores: {
            self: TWEyes::Configure::Controller::Stores.new,
            kitamura: {
              self: TWEyes::Configure::Controller::Stores::Kitamura.new,
            },
            map_camera: {
              self: TWEyes::Configure::Controller::Stores::MapCamera.new,
            },
            champ_camera: {
              self: TWEyes::Configure::Controller::Stores::ChampCamera.new,
            },
            hardoff: {
              self: TWEyes::Configure::Controller::Stores::Hardoff.new,
            },
            camera_no_naniwa: {
              self: TWEyes::Configure::Controller::Stores::CameraNoNaniwa.new,
            },
            fujiya_camera: {
              self: TWEyes::Configure::Controller::Stores::FujiyaCamera.new,
            },
          },
          free_markets: {
            self: TWEyes::Configure::Controller::FreeMarkets.new,
            mercari: {
              self: TWEyes::Configure::Controller::FreeMarkets::Mercari.new,
            },
            rakuma: {
              self: TWEyes::Configure::Controller::FreeMarkets::Rakuma.new,
            },
            fril: {
              self: TWEyes::Configure::Controller::FreeMarkets::Fril.new,
            },
          },
          japan_post: {
            self: TWEyes::Configure::Controller::JapanPost.new,
            ems: {
              self: TWEyes::Configure::Controller::JapanPost::EMS.new,
            },
          },
          amazon: {
            developer: {
              self: TWEyes::Configure::Controller::Amazon::Developer.new,
              jp: {
                self: TWEyes::Configure::Controller::Amazon::Developer::Jp.new,
              },
            },
            jp: {
              mechanize: TWEyes::Configure::Controller::Amazon::Jp::Mechanize.new,
            },
            mws: {
              self: TWEyes::Configure::Controller::Amazon::MWS.new,
              feeds: TWEyes::Configure::Controller::Amazon::MWS::Feeds.new,
            },
          },
          yahoo: {
            common: TWEyes::Configure::Controller::Yahoo.new,
            auth: TWEyes::Configure::Controller::Yahoo::Auth.new,
            developer: {
              self: TWEyes::Configure::Controller::Yahoo::Developer.new,
            },
            auctions: {
              self: TWEyes::Configure::Controller::Yahoo::Auctions.new,
              mechanize: TWEyes::Configure::Controller::Yahoo::Auctions::Mechanize.new,
            },
            api: {
              shopping: TWEyes::Configure::Controller::Yahoo::API::Shopping.new,
            }
          },
          ebay: {
            trading: {
              self: TWEyes::Configure::Controller::Ebay::Trading.new,
            },
            finding: {
              self: TWEyes::Configure::Controller::Ebay::Finding.new,
              production: {
                self: TWEyes::Configure::Controller::Ebay::Finding::Production.new,
              },
            },
            business_policies_management: {
              self: TWEyes::Configure::Controller::Ebay::BusinessPoliciesManagement.new,
              production: {
                self: TWEyes::Configure::Controller::Ebay::BusinessPoliciesManagement::Production.new,
              },
            },
            mechanize: {
              self: TWEyes::Configure::Controller::Ebay::Mechanize.new,
            },
          },
        },
        system: {
          user: TWEyes::Configure::System::User.new,
          date: TWEyes::Configure::System::DateX.new,
          directory: TWEyes::Configure::System::Directory.new,
          net: TWEyes::Configure::System::Net.new,
          resources: TWEyes::Configure::System::Resources.new,
          products: TWEyes::Configure::System::Products.new,
        },
        formatter: {
          chatwork: {
            self: TWEyes::Configure::Formatter::ChatWork.new,
          },
          analysis: {
            self: TWEyes::Configure::Formatter::Analysis.new,
            user: {
              self: TWEyes::Configure::Formatter::Analysis::User.new,
            },
            system: {
              self: TWEyes::Configure::Formatter::Analysis::System.new,
            },
          },
          exception: TWEyes::Configure::Formatter::Exception.new,
          controller: {
            monitor: {
              self: TWEyes::Configure::Formatter::Controller::Monitor.new,
              process: {
                self: TWEyes::Configure::Formatter::Controller::Monitor::Process.new,
              },
            },
            support: {
              self: TWEyes::Configure::Formatter::Controller::Support.new,
            },
            stores: {
              kitamura: {
                self: TWEyes::Configure::Formatter::Controller::Stores::Kitamura.new,
              },
              camera_no_naniwa: {
                self: TWEyes::Configure::Formatter::Controller::Stores::CameraNoNaniwa.new,
              },
              map_camera: {
                self: TWEyes::Configure::Formatter::Controller::Stores::MapCamera.new,
              },
              champ_camera: {
                self: TWEyes::Configure::Formatter::Controller::Stores::ChampCamera.new,
              },
              hardoff: {
                self: TWEyes::Configure::Formatter::Controller::Stores::Hardoff.new,
              },
              fujiya_camera: {
                self: TWEyes::Configure::Formatter::Controller::Stores::FujiyaCamera.new,
              },
            },
            free_markets: {
              mercari: {
                self: TWEyes::Configure::Formatter::Controller::FreeMarkets::Mercari.new,
              },
              rakuma: {
                self: TWEyes::Configure::Formatter::Controller::FreeMarkets::Rakuma.new,
              },
              fril: {
                self: TWEyes::Configure::Formatter::Controller::FreeMarkets::Fril.new,
              },
            },
            yahoo: {
              api: {
                self: TWEyes::Configure::Formatter::Controller::Yahoo::API.new,
                auctions: {
                  self: TWEyes::Configure::Formatter::Controller::Yahoo::API::Auctions.new,
                }
              },
              auth: TWEyes::Configure::Formatter::Controller::Yahoo::Auth.new,
              developer: {
                self: TWEyes::Configure::Formatter::Controller::Yahoo::Developer.new,
              },
              mechanize: {
                self: TWEyes::Configure::Formatter::Controller::Yahoo::Mechanize.new,
              },
              auctions: {
                self: TWEyes::Configure::Formatter::Controller::Yahoo::Auctions.new,
              },
              shopping: TWEyes::Configure::Formatter::Controller::Yahoo::Shopping.new
            },
            ebay: {
              us: TWEyes::Configure::Formatter::Controller::Ebay::Us.new,
            },
            amazon: {
              developer: {
                self: TWEyes::Configure::Formatter::Controller::Amazon::Developer.new,
                jp: {
                  self: TWEyes::Configure::Formatter::Controller::Amazon::Developer::Jp.new,
                },
              },
              mws: {
                feeds: TWEyes::Configure::Formatter::Controller::Amazon::MWS::Feeds.new,
                orders: TWEyes::Configure::Formatter::Controller::Amazon::MWS::Orders.new,
              }
            },
          },
          orchestrator: {
            yahoo: {
              auctions: TWEyes::Configure::Formatter::Orchestrator::Yahoo::Auctions.new,
              shopping: TWEyes::Configure::Formatter::Orchestrator::Yahoo::Shopping.new,
            },
            amazon: {
              jp: TWEyes::Configure::Formatter::Orchestrator::Amazon::Jp.new,
            },
            ebay: {
              us: TWEyes::Configure::Formatter::Orchestrator::Ebay::Us.new,
            }
          }
        },
        builder: {
          mysql: {
            self: TWEyes::Configure::Builder::MySQL.new,
            item: TWEyes::Configure::Builder::MySQL::Item.new,
            bids: TWEyes::Configure::Builder::MySQL::Bids.new,
          }
        }
      }
    end

    def initialize_controller

      @controller = {
        daemon: {
          signal: TWEyes::Controller::Daemon::SignalX.new(@configure),
        },
        flow: {
          self: TWEyes::Controller::Flow.new(@configure),
          contract: {
            self: TWEyes::Controller::Flow::Contract.new(@configure),
            market_screening: {
              self: TWEyes::Controller::Flow::Contract::MarketScreening.new(@configure),
            },
            merchandise_management: {
              self: TWEyes::Controller::Flow::Contract::MerchandiseManagement.new(@configure),
            },
          },
          chatwork: {
            self: TWEyes::Controller::Flow::ChatWork.new(@configure),
          },
          yahoo: {
            self: TWEyes::Controller::Flow::Yahoo.new(@configure),
            auctions: {
              self: TWEyes::Controller::Flow::Yahoo::Auctions.new(@configure),
              seller: {
                self: TWEyes::Controller::Flow::Yahoo::Auctions::Seller.new(@configure),
              },
              buyer: {
                self: TWEyes::Controller::Flow::Yahoo::Auctions::Buyer.new(@configure),
              },
            },
          },
          ebay: {
            self: TWEyes::Controller::Flow::Ebay.new(@configure),
            us: {
              self: TWEyes::Controller::Flow::Ebay::Us.new(@configure),
            },
          },
          amazon: {
            self: TWEyes::Controller::Flow::Amazon.new(@configure),
            jp: {
              self: TWEyes::Controller::Flow::Amazon::Jp.new(@configure),
            },
          },
        },
        support: {
          self: TWEyes::Controller::Support.new(@configure),
        },
        unix: {
          command: {
            self: TWEyes::Controller::UNIX::Command.new(@configure),
          },
        },
        monitor: {
          process: {
            self: TWEyes::Controller::Monitor::ProcessX.new(@configure),
          },
        },
        stores: {
          kitamura: {
            self: TWEyes::Controller::Stores::Kitamura.new(@configure),
          },
          map_camera: {
            self: TWEyes::Controller::Stores::MapCamera.new(@configure),
          },
          champ_camera: {
            self: TWEyes::Controller::Stores::ChampCamera.new(@configure),
          },
          hardoff: {
            self: TWEyes::Controller::Stores::Hardoff.new(@configure),
          },
          camera_no_naniwa: {
            self: TWEyes::Controller::Stores::CameraNoNaniwa.new(@configure),
          },
          fujiya_camera: {
            self: TWEyes::Controller::Stores::FujiyaCamera.new(@configure),
          },
        },
        free_markets: {
          mercari: {
            self: TWEyes::Controller::FreeMarkets::Mercari.new(@configure),
          },
          rakuma: {
            self: TWEyes::Controller::FreeMarkets::Rakuma.new(@configure),
          },
          fril: {
            self: TWEyes::Controller::FreeMarkets::Fril.new(@configure),
          },
        },
        chatwork: {
          self: TWEyes::Controller::ChatWork.new(@configure),
        },
        japan_post: {
          ems: {
            self: TWEyes::Controller::JapanPost::EMS.new(@configure),
          },
        },
        yahoo: {
          auth: {
            self: TWEyes::Controller::Yahoo::Auth.new(@configure),
            seller: TWEyes::Controller::Yahoo::Auth::Seller.new(@configure),
            buyer: TWEyes::Controller::Yahoo::Auth::Buyer.new(@configure),
          },
          developer: {
            self: TWEyes::Controller::Yahoo::Developer.new(@configure),
          },
          auctions: {
            self: TWEyes::Controller::Yahoo::Auctions.new(@configure),
            mechanize: TWEyes::Controller::Yahoo::Auctions::MechanizeX.new(@configure),
          },
          shopping: TWEyes::Controller::Yahoo::Shopping.new(@configure),
          api: {
            auctions: TWEyes::Controller::Yahoo::API::Auctions.new(@configure),
            shopping: {
              self: TWEyes::Controller::Yahoo::API::Shopping.new(@configure),
              circus: TWEyes::Controller::Yahoo::API::Shopping::Circus.new(@configure),
            }
          }
        },
        ebay: {
          mechanize: {
            self: TWEyes::Controller::Ebay::MechanizeX.new(@configure),
          },
          trading: {
            self: TWEyes::Controller::Ebay::Trading.new(@configure),
            production: TWEyes::Controller::Ebay::Trading::Production.new(@configure),
          },
          finding: {
            self: TWEyes::Controller::Ebay::Finding.new(@configure),
            production: {
              self: TWEyes::Controller::Ebay::Finding::Production.new(@configure),
            },
          },
          business_policies_management: {
            self: TWEyes::Controller::Ebay::BusinessPoliciesManagement.new(@configure),
            production: {
              self: TWEyes::Controller::Ebay::BusinessPoliciesManagement::Production.new(@configure),
            },
          },
        },
        amazon: {
          developer: {
            self: TWEyes::Controller::Amazon::Developer.new(@configure),
            jp: {
              self: TWEyes::Controller::Amazon::Developer::Jp.new(@configure),
            },
          },
          jp: {
            mechanize: TWEyes::Controller::Amazon::Jp::MechanizeX.new(@configure),
          },
          mws: {
            feeds: TWEyes::Controller::Amazon::MWS::Feeds.new(@configure),
            orders: TWEyes::Controller::Amazon::MWS::Orders.new(@configure),
            reports: TWEyes::Controller::Amazon::MWS::Reports.new(@configure),
            fulfillment_inventory: TWEyes::Controller::Amazon::MWS::FulfillmentInventory.new(@configure),
            products: TWEyes::Controller::Amazon::MWS::Products.new(@configure)
          }
        }
      }
    end

    def initialize_database
      @database = {
        mysql: TWEyes::Database::MySQL.new(@configure)
      }
    end

    def initialize_driver
      @driver = {
        web: {
          selenium: TWEyes::Driver::Web::SeleniumX.new(@configure),
          mechanize: TWEyes::Driver::Web::MechanizeX.new(@configure),
          proxies: {
            mpp: TWEyes::Driver::Web::Proxies::MPP.new(@configure),
          }
        }
      }
    end

    def initialize_api

      @api = {
        yahoo: {
          auctions: TWEyes::API::Yahoo::Auctions.new(@configure),
          shopping: {
            self: TWEyes::API::Yahoo::Shopping.new(@configure),
            circus: TWEyes::API::Yahoo::Shopping::Circus.new(@configure),
          }
        },
        chatwork: TWEyes::API::ChatWork.new(@configure),
        amazon: {
          mws: {
            feeds: TWEyes::API::Amazon::MWSX::Feeds.new(@configure),
            orders: TWEyes::API::Amazon::MWSX::Orders.new(@configure),
            reports: TWEyes::API::Amazon::MWSX::Reports.new(@configure),
            products: TWEyes::API::Amazon::MWSX::Products.new(@configure),
            fulfillment_inventory: TWEyes::API::Amazon::MWSX::FulfillmentInventory.new(@configure),
          }
        },
        ebay: {
          trading: {
            self: TWEyes::API::Ebay::Trading.new(@configure),
            production: TWEyes::API::Ebay::Trading::Production.new(@configure),
          },
          finding: {
            self: TWEyes::API::Ebay::Finding.new(@configure),
            production: {
              self: TWEyes::API::Ebay::Finding::Production.new(@configure),
            },
          },
          business_policies_management: {
            self: TWEyes::API::Ebay::BusinessPoliciesManagement.new(@configure),
            production: {
              self: TWEyes::API::Ebay::BusinessPoliciesManagement::Production.new(@configure),
            },
          },
        },
      }
    end

    def initialize_formatter

      @formatter = {
        exception: TWEyes::Formatter::Exception.new(@configure),
        chatwork: {
          self: TWEyes::Formatter::ChatWork.new(@configure),
        },
        analysis: {
          self: TWEyes::Formatter::Analysis.new(@configure),
          user: {
            self: TWEyes::Formatter::Analysis::User.new(@configure),
          },
          system: {
            self: TWEyes::Formatter::Analysis::System.new(@configure),
          },
        },
        controller: {
          monitor: {
            self: TWEyes::Formatter::Controller::Monitor.new(@configure),
            process: {
              self: TWEyes::Formatter::Controller::Monitor::Process.new(@configure),
            },
          },
          support: {
            self: TWEyes::Formatter::Controller::Support.new(@configure),
          },
          stores: {
            kitamura: {
              self: TWEyes::Formatter::Controller::Stores::Kitamura.new(@configure),
            },
            camera_no_naniwa: {
              self: TWEyes::Formatter::Controller::Stores::CameraNoNaniwa.new(@configure),
            },
            map_camera: {
              self: TWEyes::Formatter::Controller::Stores::MapCamera.new(@configure),
            },
            champ_camera: {
              self: TWEyes::Formatter::Controller::Stores::ChampCamera.new(@configure),
            },
            hardoff: {
              self: TWEyes::Formatter::Controller::Stores::Hardoff.new(@configure),
            },
            fujiya_camera: {
              self: TWEyes::Formatter::Controller::Stores::FujiyaCamera.new(@configure),
            },
          },
          free_markets: {
            mercari: {
              self: TWEyes::Formatter::Controller::FreeMarkets::Mercari.new(@configure),
            },
            rakuma: {
              self: TWEyes::Formatter::Controller::FreeMarkets::Rakuma.new(@configure),
            },
            fril: {
              self: TWEyes::Formatter::Controller::FreeMarkets::Fril.new(@configure),
            },
          },
          yahoo: {
            developer: {
              self: TWEyes::Formatter::Controller::Yahoo::Developer.new(@configure),
            },
            mechanize: {
              self: TWEyes::Formatter::Controller::Yahoo::Mechanize.new(@configure),
            },
            auctions: {
              self: TWEyes::Formatter::Controller::Yahoo::Auctions.new(@configure),
              api: TWEyes::Formatter::Controller::Yahoo::Auctions::API.new(@configure),
            },
            shopping: TWEyes::Formatter::Controller::Yahoo::Shopping.new(@configure),
          },
          ebay: {
            us: TWEyes::Formatter::Controller::Ebay::Us.new(@configure),
          },
          amazon: {
            developer: {
              self: TWEyes::Formatter::Controller::Amazon::Developer.new(@configure),
              jp: {
                self: TWEyes::Formatter::Controller::Amazon::Developer::Jp.new(@configure),
              },
            },
            mws: {
              feeds: TWEyes::Formatter::Controller::Amazon::MWS::Feeds.new(@configure),
              orders: TWEyes::Formatter::Controller::Amazon::MWS::Orders.new(@configure),
            }
          }
        },
        orchestrator: {
          yahoo: {
            auctions: TWEyes::Formatter::Orchestrator::Yahoo::Auctions.new(@configure),
            shopping: TWEyes::Formatter::Orchestrator::Yahoo::Shopping.new(@configure),
          },
          amazon: {
            jp: TWEyes::Formatter::Orchestrator::Amazon::Jp.new(@configure),
          },
          ebay: {
            us: TWEyes::Formatter::Orchestrator::Ebay::Us.new(@configure),
          }
        },
      }
    end

    def initialize_logger
      @logger = {
        system: TWEyes::LoggerX::System.new(@configure),
        user:   TWEyes::LoggerX::User.new(@configure)
      }
    end

    def initialize_builder

      @builder = {
        mysql: {
          common:  TWEyes::Builder::MySQL.new(@configure),
          account: TWEyes::Builder::MySQL::Account.new(@configure),
          system: {
            self:  TWEyes::Builder::MySQL::System.new(@configure),
            research: {
              self: TWEyes::Builder::MySQL::System::Research.new(@configure),
              analysis: {
                self: TWEyes::Builder::MySQL::System::Research::Analysis.new(@configure),
                archive: {
                  self: TWEyes::Builder::MySQL::System::Research::Analysis::Archive.new(@configure),
                },
              },
            },
          },
          research: {
            self: TWEyes::Builder::MySQL::Research.new(@configure),
            new: {
              arrival: {
                self: TWEyes::Builder::MySQL::Research::NewArrival.new(@configure),
              }
            },
            stores: {
              self: TWEyes::Builder::MySQL::Research::Stores.new(@configure),
            },
            watch: {
              list: {
                self: TWEyes::Builder::MySQL::Research::WatchList.new(@configure),
              }
            },
            analysis: {
              self: TWEyes::Builder::MySQL::Research::Analysis.new(@configure),
              archive: {
                self: TWEyes::Builder::MySQL::Research::Analysis::Archive.new(@configure),
              },
            },
            yahoo: {
              auctions: {
                search: {
                  self: TWEyes::Builder::MySQL::Research::Yahoo::Auctions::Search.new(@configure),
                },
              },
            },
            free_markets: {
              self: TWEyes::Builder::MySQL::Research::FreeMarkets.new(@configure),
              search: {
                self: TWEyes::Builder::MySQL::Research::FreeMarkets::Search.new(@configure),
              },
              watch: {
                self: TWEyes::Builder::MySQL::Research::FreeMarkets::Watch.new(@configure),
              },
            },
          },
          bids: {
            self: TWEyes::Builder::MySQL::Bids.new(@configure),
          },
          item: {
            self: TWEyes::Builder::MySQL::Item.new(@configure),
            yahoo: {
              auctions: TWEyes::Builder::MySQL::Item::Yahoo::Auctions.new(@configure),
            },
            ebay: {
              policy: {
                self: TWEyes::Builder::MySQL::Item::Ebay::Policy.new(@configure),
                payment: {
                  self: TWEyes::Builder::MySQL::Item::Ebay::Policy::Payment.new(@configure),
                },
                return: {
                  self: TWEyes::Builder::MySQL::Item::Ebay::Policy::Return.new(@configure),
                },
                shipping: {
                  self: TWEyes::Builder::MySQL::Item::Ebay::Policy::Shipping.new(@configure),
                },
              },
              us: TWEyes::Builder::MySQL::Item::Ebay::Us.new(@configure),
            },
            amazon: {
              jp: TWEyes::Builder::MySQL::Item::Amazon::Jp.new(@configure),
            },
          },
          auth: {
            self: TWEyes::Builder::MySQL::Auth.new(@configure),
            yahoo: {
              self: TWEyes::Builder::MySQL::Auth::Yahoo.new(@configure),
              seller: TWEyes::Builder::MySQL::Auth::Yahoo::Seller.new(@configure),
              buyer: TWEyes::Builder::MySQL::Auth::Yahoo::Buyer.new(@configure),
            }
          },
          support: {
            self: TWEyes::Builder::MySQL::Support.new(@configure),
            contact: {
              self: TWEyes::Builder::MySQL::Support::Contact.new(@configure),
            }
          },
          monitor: {
            self: TWEyes::Builder::MySQL::Monitor.new(@configure),
            process: {
              self: TWEyes::Builder::MySQL::Monitor::Process.new(@configure),
            }
          },
          setting: {
            self: TWEyes::Builder::MySQL::Setting.new(@configure),
            item: {
              self: TWEyes::Builder::MySQL::Setting::Item.new(@configure),
              my_pattern: {
                self: TWEyes::Builder::MySQL::Setting::Item::MyPattern.new(@configure),
              },
            },
          },
          profiling: {
            self: TWEyes::Builder::MySQL::Profiling.new(@configure),
            api: {
              self: TWEyes::Builder::MySQL::Profiling::APIS.new(@configure),
              yahoo: {
                self: TWEyes::Builder::MySQL::Profiling::APIS::Yahoo.new(@configure),
                auctions: {
                  self: TWEyes::Builder::MySQL::Profiling::APIS::Yahoo::Auctions.new(@configure),
                  seller: {
                    self: TWEyes::Builder::MySQL::Profiling::APIS::Yahoo::Auctions::Seller.new(@configure),
                  },
                  buyer: {
                    self: TWEyes::Builder::MySQL::Profiling::APIS::Yahoo::Auctions::Buyer.new(@configure),
                  },
                },
              },
            },
          },
        },
      }
    end

    def initialize_orchestrator
      @orchestrator = {
        member: TWEyes::Orchestrator::Member.new(@configure),
        yahoo: {
          auctions: TWEyes::Orchestrator::Yahoo::Auctions.new(@configure),
          shopping: TWEyes::Orchestrator::Yahoo::Shopping.new(@configure),
        },
        amazon: {
          jp: TWEyes::Orchestrator::Amazon::Jp.new(@configure),
        },
        ebay: {
          us: TWEyes::Orchestrator::Ebay::Us.new(@configure),
        }
      }
    end

  end

end
