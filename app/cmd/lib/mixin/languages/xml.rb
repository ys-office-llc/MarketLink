module TWEyes
  module Mixin
    module Languages
      module XML

        def dump_xml(raw_xml)
          shaped_xml = ''

          doc = REXML::Document.new(raw_xml)
          formatter = REXML::Formatters::Pretty.new
          formatter.write(doc.root, shaped_xml)

          puts shaped_xml
        end

        def xml_to_hash(xml)
          XmlSimple.xml_in(xml)
        end

      end ### XML [END]
    end ### Languages [END]
  end ### Mixin [END]
end ### TWEyes [END]
