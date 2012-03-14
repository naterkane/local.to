# Read memcached.c and add directive
File.open('memcached.c') do |f|
  content = f.read
  content.gsub!(/#ifdef TCP_NOPUSH/, "#undef TCP_NOPUSH\n#ifdef TCP_NOPUSH")
  puts content
end
