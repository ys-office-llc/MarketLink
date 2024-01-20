require_relative '../lib/application'

module TWEyes

  class Support < Application
    public

    def initialize
      super

      @builder[:mysql][:common].connect(@database[:mysql])
    end

    def forward(room_name)

      mail = Hash.new{|h,k| h[k] = {}}

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )

      @api[:chatwork].set_default_token

      rfc2822 = Mail.new(STDIN.read)

      mail.store(:from, rfc2822.from.join(','))
      mail.store(:to, rfc2822.to.join(','))
      mail.store(:subject, rfc2822.subject)
      mail.store(:date, rfc2822.date)
      mail.store(:body, rfc2822.body.decoded.toutf8[0..512])
      mail.store(:envelope_from, rfc2822.envelope_from)
      mail.store(:envelope_to, rfc2822.envelope_from)

=begin
      if rfc2822.multipart?

        rfc2822.text_part.decoded
        rfc2822.html_part.decoded

        pp rfc2822.attachments.first.filename
        pp rfc2822.first.mime_type

        File.open("picture.jpg", "w+b") do |f|

          f.write(rfc2822.attachments.first.body.decoded)
        end
      end
=end

      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices[room_name.to_s],
        sprintf(
          "[info]"+
          "[title]送信者[/title]%{from}"+
          "[title]受信者[/title]%{to}"+
          "[title]%{subject}[/title]"+
          "%{body}"+
          "[/info]",
          mail
        )
      )
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    def contact

      @logger[:system].open(File.basename(__FILE__))
      @logger[:system].info(
        sprintf("%s::%s to start", self.class, __method__)
      )
      @builder[:mysql][:account].get_records.each do |account|

        @configure[:system][:user].account = account
        @logger[:user].open
        @api[:chatwork].initializeX
        @api[:chatwork].set_default_token

        unless @controller[:flow][:contract][:self].permit? and
               @controller[:flow][:chatwork][:self].permit?

          next
        end

        @builder[:mysql][:support][:contact][:self]
        .get_records
        .each do |message|

          message = message.map do |k,v|

            if k.to_s.match(/support_contact_image_/) and v

              [
                k,
                sprintf(
                  "%s/%s",
                  @configure[:system][:net]
                  .get_https_uri+
                  @configure[:system][:directory]
                  .get_relative_spool_path,
                  v
                )
              ]
            else

              [k, v]
            end
          end.to_h

          begin

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['customer'],
              @formatter[:controller][:support][:self].contact(
                @controller[:support][:self].contact(message)
              )
            )

            @builder[:mysql][:support][:contact][:self].update(
              message[:support_contact_id],
              {
                submitted: 1
              }
            )

          rescue TWEyes::Exception::Controller::Support => e

            @api[:chatwork].push_message(
              @configure[:api][:chatwork].room_indices['system'],
              @formatter[:exception].handle(e)
            )
          end

        end

      end
    rescue => e

      @logger[:system].crit(@formatter[:exception].handle(e))
      @api[:chatwork].push_message(
        @configure[:api][:chatwork].room_indices['system'],
        @formatter[:exception].handle(e)
      )
    end

    protected

    private

  end ### class Support [END]
end
